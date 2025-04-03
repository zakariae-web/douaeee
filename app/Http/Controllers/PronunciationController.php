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
            'attempted_word' => 'required|string',
            'success' => 'required|boolean'
        ]);
    
        $attempt = PronunciationAttempt::create([
            'user_id' => auth()->id(),
            'letter' => $validated['letter'],
            'attempted_word' => $validated['attempted_word'],
            'success' => $validated['success']
        ]);
    
        if ($validated['success']) {
            // Nouvelle lettre
            $newLetter = Letter::inRandomOrder()->first()->letter;
            
            // 🔴 FORCER LA MISE À JOUR DE LA SESSION
            session(['currentLetter' => $newLetter]);
            session()->save(); // <- Cette ligne force l'enregistrement de la session
    
            return response()->json([
                'message' => 'Attempt saved successfully',
                'newLetter' => $newLetter,
                'data' => $attempt
            ]);
        }
    
        return response()->json([
            'message' => 'Attempt saved successfully',
            'data' => $attempt
        ]);
    }
    
    
    



    public function getRandomLetter()
    {
        $letter = Letter::inRandomOrder()->first();
        session(['currentLetter' => $letter->letter]); // Stocker en session
        return response()->json(['letter' => $letter->letter]);
    }
    

}