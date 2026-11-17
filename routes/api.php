<?php

use App\Http\Controllers\Api\CourtApiController;
use Illuminate\Support\Facades\Route;

Route::apiResource('courts', CourtApiController::class);