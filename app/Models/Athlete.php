<?php

// app/Models/Athlete.php
// A model is a PHP class that represents one database table.
// Laravel guesses the table name from the class name: Athlete → "athletes".
// The namespace below is this class's "address". It matches the folder app/Models.

namespace App\Models;

// "use" imports classes from other files so we can refer to them by short name
use Database\Factories\AthleteFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// "extends Model" gives Athlete all of Eloquent's database powers:
// Athlete::all(), Athlete::where(...), $athlete->save(), and more.
class Athlete extends Model
{
    // HasFactory connects this model to AthleteFactory. That is what makes
    // Athlete::factory()->create() work in tests.
    /** @use HasFactory<AthleteFactory> */
    use HasFactory;

    // Columns that may be filled all at once, as in Athlete::create([...]).
    // Anything not listed is ignored, which stops someone sneaking extra
    // fields (like "id") into a form submission.
    protected $fillable = [
        'name', 'sport', 'position', 'grad_year', 'state', 'gpa', 'bio',
    ];

    // Casts decide what type each column has in PHP and in JSON.
    // The database hands decimals back as text, so without 'gpa' => 'float'
    // the API would send "3.80" (a string) instead of 3.8 (a number).
    protected function casts(): array
    {
        return [
            'grad_year' => 'integer',
            'gpa' => 'float',
        ];
    }
}
