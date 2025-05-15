<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\PronunciationController;
use App\Http\Controllers\TeacherDashboardController;
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
Route::get('/jeu', function () {return view('game.index');})->middleware('auth')->name('jeu');

Route::post('/verify-pronunciation', function (Request $request) {
    $expectedWord = Session::get('currentLetter', 'A');
    $spokenWord = $request->input('word');

    return response()->json([
        'correct' => strtolower($spokenWord) === strtolower($expectedWord)
    ]);
});
Route::get('/random-letter', [PronunciationController::class, 'getRandomLetter']);
Route::get('/letters', function (Request $request) {
    $stageId = $request->query('stage_id', 1);
    $userId = Auth::id();

    if (!$userId) {
        return response()->json(['error' => 'Non authentifié'], 401);
    }

    // Lettres déjà réussies par l'utilisateur
    $successfulLetters = PronunciationAttempt::where('user_id', $userId)
        ->where('success', true)
        ->pluck('letter');

    // Lettres du stage non encore réussies
    $letters = Letter::where('stage_id', $stageId)
        ->whereNotIn('letter', $successfulLetters)
        ->pluck('letter');

    return response()->json($letters);
});





Route::post('/attempt', [PronunciationController::class, 'store'])->middleware('auth');

Route::get('/results', function () {
    $attempts = PronunciationAttempt::where('user_id', Auth::id())->latest()->paginate(10);
    return view('game.results', compact('attempts'));
})->name('results');

Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/user/{id}/attempts', [TeacherDashboardController::class, 'showUserAttempts'])->name('admin.user.attempts');

// Routes pour la gestion des lettres (admin)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('letters', App\Http\Controllers\LetterController::class);
});

