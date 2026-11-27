<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->string('day_type', 20); // weekday/weekend/holiday
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedInteger('price_per_hour');
            $table->timestamps();
            $table->index(['day_type', 'start_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_rules');
    }
};
