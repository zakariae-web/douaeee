<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PronunciationAttempt;
use App\Models\Letter;
use App\Models\User;

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
        $user = auth()->user();

        $attempt = PronunciationAttempt::create([
            'user_id' => $user->id,
            'letter' => $validated['letter'],
            'attempted_word' => $validated['attempted_word'] ?? null,
            'success' => $validated['success'],
            'skipped' => $skipped
        ]);

        $progression = null;
        if ($validated['success'] && !$skipped) {
            // Ajouter de l'XP uniquement si la tentative est réussie et non skippée
            $isWord = !empty($validated['attempted_word']);
            $progression = $user->addXp($isWord);
        }

        // Générer une nouvelle lettre uniquement si succès ou skip
        if ($validated['success'] || $skipped) {
            $newLetter = Letter::inRandomOrder()->first()->letter;
            session(['currentLetter' => $newLetter]);
            session()->save();

            return response()->json([
                'message' => $skipped ? 'Lettre skippée.' : 'Tentative réussie.',
                'newLetter' => $newLetter,
                'data' => $attempt,
                'progression' => $progression
            ]);
        }

        return response()->json([
            'message' => 'Tentative enregistrée.',
            'data' => $attempt,
            'progression' => $progression
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
