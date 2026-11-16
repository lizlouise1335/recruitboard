<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // up() runs when you migrate. It describes the table to create.
    public function up(): void
    {
        // Create a table called "athletes". $table is a blueprint you add columns to.
        Schema::create('athletes', function (Blueprint $table) {
            // An auto-numbering primary key (1, 2, 3...) that uniquely identifies each row
            $table->id();

            // string = short text (up to 255 characters)
            $table->string('name');
            $table->string('sport');
            $table->string('position');

            // A small whole number that can't be negative, like 2027
            $table->unsignedSmallInteger('grad_year');

            // Text that is at most 2 characters long, like "NJ"
            $table->string('state', 2);

            // A decimal with 3 digits in total, 2 of them after the dot: 3.80, 4.00
            $table->decimal('gpa', 3, 2);

            // text = long text. nullable() means this column is allowed to be empty.
            $table->text('bio')->nullable();

            // Adds created_at and updated_at columns that Laravel fills in for you
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    // down() undoes up(), for "php artisan migrate:rollback": it deletes the table.
    public function down(): void
    {
        Schema::dropIfExists('athletes');
    }
};
