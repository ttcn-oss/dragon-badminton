<?php

use App\Http\Controllers\CourtController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/courts');
Route::resource('courts', CourtController::class)->except('show');
