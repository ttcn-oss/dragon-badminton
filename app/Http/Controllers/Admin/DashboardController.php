<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Court;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
                $stats = [
            'courts'  => Court::count(),
            'users'   => User::where('role', 'customer')->count(),
            'today'   => \Illuminate\Support\Facades\DB::table('booking_slots')
                            ->whereDate('slot_start', today())
                            ->where('active_flag', 1)
                            ->distinct()->count('booking_id'),
            'pending' => Booking::where('status', 'pending')->count(),
            'revenue' => Booking::where('status', 'confirmed')->sum('total_amount'),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
