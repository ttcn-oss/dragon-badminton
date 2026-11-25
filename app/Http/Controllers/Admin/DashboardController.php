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
            'courts' => Court::count(),
            'users' => User::where('role', 'user')->count(),
            'today' => Booking::whereDate('booking_date', today())->where('status', '!=', 'cancelled')->count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'revenue' => Booking::where('status', 'confirmed')->sum('total_price'),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
