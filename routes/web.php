<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PronunciationController;
use App\Models\PronunciationAttempt;



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/jeu', function () {return view('game.index');})->name('jeu');
Route::post('/verify-pronunciation', function (Request $request) {
    $expectedWord = 'A'; // À remplacer dynamiquement
    $spokenWord = $request->input('word');

    return response()->json([
        'correct' => strtolower($spokenWord) === strtolower($expectedWord)
    ]);
});

Route::post('/attempt', [PronunciationController::class, 'store'])->middleware('auth');

Route::get('/results', function () {$attempts = PronunciationAttempt::where('user_id', Auth::id())->get();
    return view('game.results', compact('attempts'));
});
