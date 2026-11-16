<?php

// routes/web.php: replace the whole file

use App\Http\Controllers\AthleteController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('athletes.index'));

Route::get('/athletes', [AthleteController::class, 'index'])->name('athletes.index');
Route::get('/athletes/{athlete}', [AthleteController::class, 'show'])->name('athletes.show');

Route::get('/api/athletes', [AthleteController::class, 'search']);
Route::get('/api/health', fn () => response()->json(['status' => 'ok']));
