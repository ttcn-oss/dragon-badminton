<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_series', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customer_profiles');
            $table->foreignId('court_id')->constrained('courts');
            $table->unsignedTinyInteger('weekday'); // 0=CN ... 6=T7
            $table->time('start_time');
            $table->time('end_time');
            $table->date('start_date');
            $table->unsignedTinyInteger('weeks');
            $table->unsignedTinyInteger('discount_percent')->default(0);
            $table->string('status', 20)->default('pending');
            $table->unsignedInteger('total_amount')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_series');
    }
};
