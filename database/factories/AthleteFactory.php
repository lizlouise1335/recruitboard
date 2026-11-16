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
    public function definition(): array
    {
        $positions = [
            'Soccer' => ['Goalkeeper', 'Defender', 'Midfielder', 'Forward'],
            'Basketball' => ['Point Guard', 'Shooting Guard', 'Forward', 'Center'],
            'Volleyball' => ['Setter', 'Outside Hitter', 'Libero', 'Middle Blocker'],
            'Lacrosse' => ['Attack', 'Midfield', 'Defense', 'Goalie'],
        ];
        $sport = fake()->randomElement(array_keys($positions));

        return [
            'name' => fake()->name(),
            'sport' => $sport,
            'position' => fake()->randomElement($positions[$sport]),
            'grad_year' => fake()->numberBetween(2027, 2030),
            'state' => fake()->stateAbbr(),
            'gpa' => fake()->randomFloat(2, 2.5, 4.0),
            'bio' => fake()->sentence(12),
        ];
    }
}
