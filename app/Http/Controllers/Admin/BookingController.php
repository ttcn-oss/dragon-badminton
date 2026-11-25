<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'court'])
            ->orderByDesc('booking_date')->orderByDesc('start_time')->get();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function confirm(Booking $booking)
    {
        if ($booking->status === 'pending') {
            $booking->update(['status' => 'confirmed']);
        }

        return redirect()->route('admin.bookings.index')->with('ok', 'Đã xác nhận đơn #'.$booking->id);
    }

    public function cancel(Booking $booking)
    {
        if ($booking->status !== 'cancelled') {
            $booking->update(['status' => 'cancelled']);
        }

        return redirect()->route('admin.bookings.index')->with('ok', 'Đã hủy đơn #'.$booking->id);
    }
}
