<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('court_id')->constrained('courts');
            $table->dateTime('slot_start');
            $table->unsignedInteger('price');
            $table->unsignedTinyInteger('active_flag')->nullable()->default(1); // 1 = còn hiệu lực, NULL = đã hủy
            $table->timestamps();
            $table->unique(['court_id', 'slot_start', 'active_flag']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_slots');
    }
};
