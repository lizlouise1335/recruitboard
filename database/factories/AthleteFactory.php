<?php

namespace Database\Factories;

use App\Models\Athlete;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Athlete>
 */
class AthleteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // definition() returns the values for ONE fake athlete. Every call to
    // Athlete::factory()->create() runs it again, so each athlete is different.
    public function definition(): array
    {
        // The positions that exist in each sport. The keys ('Soccer', ...) are the sports.
        $positions = [
            'Soccer' => ['Goalkeeper', 'Defender', 'Midfielder', 'Forward'],
            'Basketball' => ['Point Guard', 'Shooting Guard', 'Forward', 'Center'],
            'Volleyball' => ['Setter', 'Outside Hitter', 'Libero', 'Middle Blocker'],
            'Lacrosse' => ['Attack', 'Midfield', 'Defense', 'Goalie'],
        ];

        // fake() is Faker, a library of random but realistic data.
        // Pick a random sport first, so the position below can match it.
        $sport = fake()->randomElement(array_keys($positions));

        // Column name => value. The keys must match the migration's columns.
        return [
            // A random full name, like "Jordan Smith"
            'name' => fake()->name(),
            'sport' => $sport,
            // A random position from the chosen sport's list
            'position' => fake()->randomElement($positions[$sport]),
            // A random whole number from 2027 to 2030
            'grad_year' => fake()->numberBetween(2027, 2030),
            // A random two-letter US state, like "OH"
            'state' => fake()->stateAbbr(),
            // A random number from 2.5 to 4.0 with 2 decimal places
            'gpa' => fake()->randomFloat(2, 2.5, 4.0),
            // A random sentence of about 12 words
            'bio' => fake()->sentence(12),
        ];
    }
}
