<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PronunciationAttempt;
use App\Models\Letter;

class PronunciationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'letter' => 'required|string|max:1',
            'success' => 'required|boolean'
        ]);

        $attempt = PronunciationAttempt::create([
            'user_id' => auth()->id(),
            'letter' => $validated['letter'],
            'success' => $validated['success']
        ]);

        return response()->json([
            'message' => 'Attempt saved successfully',
            'data' => $attempt
        ]);
    }



    public function getRandomLetter()
    {
        $letter = Letter::inRandomOrder()->first();
        return response()->json(['letter' => $letter->letter]);
    }

}