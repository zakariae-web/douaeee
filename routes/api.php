<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PronunciationController;

Route::post('/attempt', [PronunciationController::class, 'store']);
