<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Court extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'status', 'price_per_hour'];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
