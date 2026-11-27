<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Court extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image_url', 'status'];

    // Các đơn đặt của sân này (đi qua bảng booking_slots)
    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class, 'booking_slots', 'court_id', 'booking_id')->distinct();
    }

    // Giá hiển thị = giá thấp nhất trong bảng giá (bảng pricing_rules)
    public function getPricePerHourAttribute()
    {
        return \Illuminate\Support\Facades\DB::table('pricing_rules')->min('price_per_hour') ?? 0;
    }
}