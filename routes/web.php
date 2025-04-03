<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\PronunciationController;
use App\Models\PronunciationAttempt;
use App\Models\Letter;


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/get-letter', function () {
    return response()->json(['letter' => Letter::inRandomOrder()->first()->value]);
});
Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/jeu', function () {return view('game.index');})->name('jeu');
Route::post('/verify-pronunciation', function (Request $request) {
    $expectedWord = Session::get('currentLetter', 'A');
    $spokenWord = $request->input('word');

    return response()->json([
        'correct' => strtolower($spokenWord) === strtolower($expectedWord)
    ]);
});
Route::get('/random-letter', [PronunciationController::class, 'getRandomLetter']);
Route::get('/letters', function () {
    return response()->json(Letter::pluck('letter'));
});

Route::post('/attempt', [PronunciationController::class, 'store'])->middleware('auth');

Route::get('/results', function () {$attempts = PronunciationAttempt::where('user_id', Auth::id())->get();
    return view('game.results', compact('attempts'));
});
