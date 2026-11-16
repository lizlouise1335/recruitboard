<?php

// database/seeders/AthleteSeeder.php
// Fixed demo data. It runs in production too, so it can't use Faker.

namespace Database\Seeders;

use App\Models\Athlete;
use Illuminate\Database\Seeder;

class AthleteSeeder extends Seeder
{
    public function run(): void
    {
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

        foreach ($athletes as [$name, $sport, $position, $year, $state, $gpa]) {
            // updateOrCreate makes it safe to run the seeder again
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
