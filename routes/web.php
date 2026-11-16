<?php

// routes/web.php
// The app's table of contents: for each URL, which code runs.

use App\Http\Controllers\AthleteController;
use Illuminate\Support\Facades\Route;

// Visiting the bare site address sends you to the athlete list.
// "fn () => ..." is a short inline function (an arrow function).
Route::get('/', fn () => redirect()->route('athletes.index'));

// Route::get(URL, [Controller, method]) means: for GET requests to this URL,
// run this controller method. ->name() gives the route a nickname, so views
// can write route('athletes.index') instead of hard-coding "/athletes".
Route::get('/athletes', [AthleteController::class, 'index'])->name('athletes.index');

// {athlete} is a placeholder: /athletes/7 passes 7, and Laravel looks up athlete #7
Route::get('/athletes/{athlete}', [AthleteController::class, 'show'])->name('athletes.show');

// JSON endpoint that feeds the TypeScript live search
Route::get('/api/athletes', [AthleteController::class, 'search']);

// A tiny "are you alive?" check. CI and Render call it after starting the app.
Route::get('/api/health', fn () => response()->json(['status' => 'ok']));
