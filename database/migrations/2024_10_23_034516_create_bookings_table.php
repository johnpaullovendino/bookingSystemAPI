<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('book_id')->nullable();
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->dateTime('duration');
            $table->string('name');
            $table->string('phoneNumber');
            $table->string('email');
            $table->float('amount');
            $table->boolean('promo');
            $table->string('promo_code')->nullable();
            $table->float('discount')->nullable();
            $table->float('total_amount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
