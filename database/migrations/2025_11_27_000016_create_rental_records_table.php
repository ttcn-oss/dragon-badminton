<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_item_id')->constrained('rental_items');
            $table->foreignId('invoice_id')->constrained('invoices');
            $table->unsignedInteger('quantity')->default(1);
            $table->dateTime('returned_at')->nullable();
            $table->string('condition', 20)->nullable(); // ok/damaged
            $table->unsignedInteger('damage_fee')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_records');
    }
};
