<?php

namespace App\Http\Controllers;

use App\Models\Court;

class HomeController extends Controller
{
    public function index()
    {
        $courts = Court::where('status', 'active')->orderBy('name')->get();

        return view('home', compact('courts'));
    }
}
