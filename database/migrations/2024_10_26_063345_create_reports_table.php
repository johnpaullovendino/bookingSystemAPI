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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->integer('book_id')->unique();
            $table->dateTime('duration')->nullable();
            $table->string('name'); 
            $table->string('service_name');
            $table->float('amount');
            $table->string('payment_method');
            $table->string('payment_status');
            $table->string('status'); 
            $table->string('note'); 
            $table->boolean('promo');
            $table->string('promo_code')->nullable();
            $table->float('discount');
            $table->float('total_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
