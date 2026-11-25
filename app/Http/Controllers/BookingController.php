<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Court;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = $request->user()->bookings()->with('court')
            ->orderByDesc('booking_date')->orderByDesc('start_time')->get();

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
        $end = $start + (int) $data['hours'];

        if ($end > 22) {
            return back()->withInput()->withErrors(['hours' => 'Giờ kết thúc không được quá 22:00.']);
        }

        $startTime = sprintf('%02d:00:00', $start);
        $endTime = sprintf('%02d:00:00', $end);

        $conflict = Booking::where('court_id', $court->id)
            ->whereDate('booking_date', $data['booking_date'])
            ->where('status', '!=', 'cancelled')
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->exists();

        if ($conflict) {
            return back()->withInput()->withErrors([
                'start_hour' => 'Khung giờ này đã có người đặt, vui lòng chọn giờ khác.',
            ]);
        }

        Booking::create([
            'user_id' => $request->user()->id,
            'court_id' => $court->id,
            'booking_date' => $data['booking_date'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'total_price' => $court->price_per_hour * (int) $data['hours'],
            'status' => 'pending',
        ]);

        return redirect()->route('user.bookings')->with('ok', 'Đặt sân thành công, vui lòng chờ xác nhận.');
    }

    public function cancel(Request $request, Booking $booking)
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        if ($booking->status !== 'cancelled') {
            $booking->update(['status' => 'cancelled']);
        }

        return redirect()->route('user.bookings')->with('ok', 'Đã hủy đơn đặt sân.');
    }
}
