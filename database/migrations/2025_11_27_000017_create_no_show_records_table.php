<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('no_show_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained('bookings');
            $table->foreignId('customer_id')->constrained('customer_profiles');
            $table->foreignId('marked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('marked_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('no_show_records');
    }
};
