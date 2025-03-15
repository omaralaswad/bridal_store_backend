<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Create users
        User::factory(10)->create();

        // Create services
        Service::factory(5)->create();

        // Create bookings with associated users and services
        Booking::factory(20)->create();

        // Create payments with associated users and bookings
        Payment::factory(20)->create();
    }
}
