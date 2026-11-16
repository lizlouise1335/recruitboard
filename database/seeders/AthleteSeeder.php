<?php

// database/seeders/AthleteSeeder.php
// A seeder loads known starting data. These are the demo athletes you will
// see on the live site. It runs in production too, where Faker isn't
// installed, so it uses a fixed list instead of fake().

namespace Database\Seeders;

use App\Models\Athlete;
use Illuminate\Database\Seeder;

class AthleteSeeder extends Seeder
{
    // run() is what "php artisan db:seed" calls
    public function run(): void
    {
        // One row per athlete: name, sport, position, grad year, state, GPA
        $athletes = [
            ['Maya Okafor', 'Soccer', 'Forward', 2027, 'NJ', 3.80],
            ['Jordan Reyes', 'Basketball', 'Point Guard', 2028, 'TX', 3.45],
            ['Ava Lindqvist', 'Volleyball', 'Setter', 2027, 'MN', 3.92],
            ['Marcus Bell', 'Lacrosse', 'Midfield', 2029, 'MD', 3.30],
            ['Priya Raman', 'Soccer', 'Goalkeeper', 2028, 'CA', 3.97],
            ['Elijah Grant', 'Basketball', 'Center', 2027, 'OH', 3.10],
            ['Sofia Moretti', 'Volleyball', 'Outside Hitter', 2029, 'FL', 3.65],
            ['Liam Carter', 'Soccer', 'Defender', 2030, 'NY', 3.55],
            ['Zoe Nakamura', 'Lacrosse', 'Attack', 2028, 'MA', 3.88],
            ['Noah Adeyemi', 'Basketball', 'Shooting Guard', 2029, 'GA', 3.20],
        ];

        // Loop over the list. The [...] after "as" unpacks each row, putting
        // its six values into six named variables in one step.
        foreach ($athletes as [$name, $sport, $position, $year, $state, $gpa]) {
            // updateOrCreate(how to find it, values to set):
            // if an athlete with this name already exists, update them,
            // otherwise create a new one. So running the seeder twice
            // never creates duplicates.
            Athlete::updateOrCreate(
                ['name' => $name],
                [
                    'sport' => $sport,
                    'position' => $position,
                    'grad_year' => $year,
                    'state' => $state,
                    'gpa' => $gpa,
                ],
            );
        }
    }
}
