<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    // "php artisan db:seed" (and "migrate --seed") always starts here.
    // The default version used User::factory(), and factories need Faker,
    // which isn't installed in production, so it would crash there.
    public function run(): void
    {
        // call() runs another seeder class. Add more seeders to this list later.
        $this->call(AthleteSeeder::class);
    }
}
