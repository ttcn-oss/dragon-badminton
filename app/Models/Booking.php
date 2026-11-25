<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'court_id', 'booking_date', 'start_time', 'end_time', 'total_price', 'status',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date:Y-m-d',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'cancelled' => 'Đã hủy',
            default => $this->status,
        };
    }

    public function statusClass(): string
    {
        return match ($this->status) {
            'pending' => 'warning text-dark',
            'confirmed' => 'success',
            default => 'secondary',
        };
    }

    public function timeRange(): string
    {
        return substr($this->start_time, 0, 5).' - '.substr($this->end_time, 0, 5);
    }
}
