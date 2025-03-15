<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users
            $table->foreignId('service_id')->constrained()->onDelete('cascade'); // Foreign key to services
            $table->date('date'); // Scheduled appointment date
            $table->enum('status', ['pending', 'confirmed', 'completed', 'canceled'])->default('pending'); // Booking status
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
