<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Athlete extends Model
{
    /** @use HasFactory<\Database\Factories\AthleteFactory> */
    use HasFactory;

    protected $fillable = [
        'name', 'sport', 'position', 'grad_year', 'state', 'gpa', 'bio',
    ];

    // Casts decide what type each column has in PHP and in JSON.
    // Without 'gpa' => 'float', the API would send "3.80" as a string.
    protected function casts(): array
    {
        return [
            'grad_year' => 'integer',
            'gpa' => 'float',
        ];
    }
}
