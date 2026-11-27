<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Court;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    // Lấy (hoặc tạo) hồ sơ khách hàng của tài khoản đang đăng nhập
    private function profileId($user): int
    {
        $p = DB::table('customer_profiles')->where('user_id', $user->id)->first();
        if ($p) {
            return $p->id;
        }

        return DB::table('customer_profiles')->insertGetId([
            'user_id' => $user->id,
            'full_name' => $user->name,
            'phone' => 'U'.str_pad($user->id, 9, '0', STR_PAD_LEFT),
            'no_show_count' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // Giá 1 ô 30 phút = giá 1 giờ / 2, tra theo loại ngày và khung giờ
    private function slotPrice(Carbon $slot): ?int
    {
        $dayType = DB::table('holidays')->where('date', $slot->toDateString())->exists()
            ? 'holiday'
            : ($slot->isWeekend() ? 'weekend' : 'weekday');

        $time = $slot->format('H:i:s');
        $rule = DB::table('pricing_rules')
            ->where('day_type', $dayType)
            ->where('start_time', '<=', $time)
            ->where('end_time', '>', $time)
            ->first();

        return $rule ? intdiv($rule->price_per_hour, 2) : null;
    }

    public function index(Request $request)
    {
        $cid = $this->profileId($request->user());
        $bookings = Booking::where('customer_id', $cid)->orderByDesc('id')->get();

        // Gắn thêm các trường cũ để giao diện hiện tại vẫn hiển thị được
        foreach ($bookings as $b) {
            $slots = DB::table('booking_slots')->where('booking_id', $b->id)->orderBy('slot_start')->get();
            $first = $slots->first();
            $last = $slots->last();

            $court = $first ? Court::find($first->court_id) : null;
            if (! $court) {
                $court = new Court;
                $court->name = '-';
            }
            $b->setRelation('court', $court);

            if ($first) {
                $b->booking_date = Carbon::parse($first->slot_start)->startOfDay();
                $b->start_time = Carbon::parse($first->slot_start)->format('H:i:s');
                $b->end_time = Carbon::parse($last->slot_start)->addMinutes(30)->format('H:i:s');
            }
            $b->total_price = $b->total_amount;
        }

        return view('bookings.index', compact('bookings'));
    }

    public function create(Court $court)
    {
        abort_if($court->status !== 'active', 404);

        return view('bookings.create', compact('court'));
    }

    public function store(Request $request, Court $court)
    {
        abort_if($court->status !== 'active', 404);

        $data = $request->validate([
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'start_hour' => ['required', 'integer', 'between:6,21'],
            'hours' => ['required', 'integer', 'between:1,4'],
        ]);

        $start = (int) $data['start_hour'];
        $hours = (int) $data['hours'];

        if ($start + $hours > 22) {
            return back()->withInput()->withErrors(['hours' => 'Giờ kết thúc không được quá 22:00.']);
        }

        // Tạo danh sách các ô 30 phút và tính giá từng ô
        $first = Carbon::parse($data['booking_date'])->setTime($start, 0);
        $slots = [];
        $total = 0;
        for ($i = 0; $i < $hours * 2; $i++) {
            $slotStart = $first->copy()->addMinutes(30 * $i);
            $price = $this->slotPrice($slotStart);
            if ($price === null) {
                return back()->withInput()->withErrors(['start_hour' => 'Chưa có bảng giá cho khung giờ này.']);
            }
            $slots[] = ['slot_start' => $slotStart->format('Y-m-d H:i:s'), 'price' => $price];
            $total += $price;
        }

        // Kiểm tra trùng giờ (chỉ tính ô còn hiệu lực)
        $conflict = DB::table('booking_slots')
            ->where('court_id', $court->id)
            ->where('active_flag', 1)
            ->whereIn('slot_start', array_column($slots, 'slot_start'))
            ->exists();

        if ($conflict) {
            return back()->withInput()->withErrors([
                'start_hour' => 'Khung giờ này đã có người đặt, vui lòng chọn giờ khác.',
            ]);
        }

        try {
            DB::transaction(function () use ($request, $court, $slots, $total) {
                $bookingId = DB::table('bookings')->insertGetId([
                    'code' => 'DB'.now()->format('ymd').strtoupper(Str::random(5)),
                    'customer_id' => $this->profileId($request->user()),
                    'created_by' => $request->user()->id,
                    'source' => 'online',
                    'status' => 'pending',
                    'total_amount' => $total,
                    'hold_expires_at' => now()->addMinutes(5),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($slots as $s) {
                    DB::table('booking_slots')->insert([
                        'booking_id' => $bookingId,
                        'court_id' => $court->id,
                        'slot_start' => $s['slot_start'],
                        'price' => $s['price'],
                        'active_flag' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });
        } catch (QueryException $e) {
            // Hai người đặt cùng lúc: ràng buộc duy nhất của DB sẽ chặn
            return back()->withInput()->withErrors([
                'start_hour' => 'Khung giờ này vừa có người đặt, vui lòng chọn giờ khác.',
            ]);
        }

        return redirect()->route('user.bookings')->with('ok', 'Đặt sân thành công, vui lòng chờ xác nhận.');
    }

    public function cancel(Request $request, Booking $booking)
    {
        abort_unless($booking->customer_id === $this->profileId($request->user()), 403);

        if ($booking->status !== 'cancelled') {
            DB::transaction(function () use ($booking) {
                DB::table('bookings')->where('id', $booking->id)->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'updated_at' => now(),
                ]);
                // Trả lại các ô giờ: active_flag để trống thì người khác đặt được
                DB::table('booking_slots')->where('booking_id', $booking->id)->update([
                    'active_flag' => null,
                    'updated_at' => now(),
                ]);
            });
        }

        return redirect()->route('user.bookings')->with('ok', 'Đã hủy đơn đặt sân.');
    }
}