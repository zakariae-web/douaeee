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
            'letter' => 'required|string|max:50',
            'attempted_word' => 'nullable|string',
            'success' => 'required|boolean',
            'skipped' => 'nullable|boolean'
        ]);

        $skipped = $validated['skipped'] ?? false;

        $attempt = PronunciationAttempt::create([
            'user_id' => auth()->id(),
            'letter' => $validated['letter'],
            'attempted_word' => $validated['attempted_word'] ?? null,
            'success' => $validated['success'],
            'skipped' => $skipped
        ]);

        // Générer une nouvelle lettre uniquement si succès ou skip
        if ($validated['success'] || $skipped) {
            $newLetter = Letter::inRandomOrder()->first()->letter;

            session(['currentLetter' => $newLetter]);
            session()->save();

            return response()->json([
                'message' => $skipped ? 'Lettre skippée.' : 'Tentative réussie.',
                'newLetter' => $newLetter,
                'data' => $attempt
            ]);
        }

        return response()->json([
            'message' => 'Tentative enregistrée.',
            'data' => $attempt
        ]);
    }



public function getRandomLetter(Request $request)
{
    $stageId = $request->query('stage_id');

    if (!$stageId) {
        return response()->json(['error' => 'stage_id is required'], 400);
    }

    $letter = Letter::where('stage_id', $stageId)->inRandomOrder()->first();

    if (!$letter) {
        return response()->json(['error' => 'No letter found for this stage'], 404);
    }

    return response()->json(['letter' => $letter->value]);
}


}
