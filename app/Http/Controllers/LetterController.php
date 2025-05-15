<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class LetterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->role !== 'teacher') {
            return redirect()->route('home')->with('error', 'Accès non autorisé');
        }

        $letters = Letter::with('stage')->paginate(10);
        $stages = Stage::all();
        return view('admin.letters.index', compact('letters', 'stages'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'teacher') {
            return redirect()->route('home')->with('error', 'Accès non autorisé');
        }

        $request->validate([
            'letter' => 'required|string|max:255',
            'stage_id' => 'required|exists:stages,id',
            'audio' => 'required|file|mimes:mp3,wav'
        ]);

        // Vérifier si la lettre existe déjà
        if (Letter::where('letter', $request->letter)->exists()) {
            return redirect()->route('admin.letters.index')
                ->with('error', 'Cette lettre existe déjà dans la base de données.');
        }

        // Créer le dossier audio/letters s'il n'existe pas
        $audioPath = public_path('audio/letters');
        if (!File::exists($audioPath)) {
            File::makeDirectory($audioPath, 0777, true);
        }

        if ($request->hasFile('audio')) {
            $fileName = strtoupper($request->letter) . '.mp3';
            $request->file('audio')->move($audioPath, $fileName);
            $audioPath = 'audio/letters/' . $fileName;
        }

        try {
            $letter = Letter::create([
                'letter' => $request->letter,
                'stage_id' => $request->stage_id,
                'audio_path' => $audioPath ?? null
            ]);

            return redirect()->route('admin.letters.index')
                ->with('success', 'Lettre ajoutée avec succès');
        } catch (\Exception $e) {
            // Si le fichier audio a été créé mais l'insertion a échoué, on le supprime
            if (isset($audioPath) && File::exists(public_path($audioPath))) {
                File::delete(public_path($audioPath));
            }
            
            return redirect()->route('admin.letters.index')
                ->with('error', 'Cette lettre existe déjà dans la base de données.');
        }
    }

    public function update(Request $request, Letter $letter)
    {
        if (Auth::user()->role !== 'teacher') {
            return redirect()->route('home')->with('error', 'Accès non autorisé');
        }

        $request->validate([
            'letter' => 'required|string|max:255',
            'stage_id' => 'required|exists:stages,id',
            'audio' => 'nullable|file|mimes:mp3,wav'
        ]);

        $data = [
            'letter' => $request->letter,
            'stage_id' => $request->stage_id
        ];

        if ($request->hasFile('audio')) {
            // Supprimer l'ancien fichier audio s'il existe
            if ($letter->audio_path) {
                $oldAudioPath = public_path($letter->audio_path);
                if (File::exists($oldAudioPath)) {
                    File::delete($oldAudioPath);
                }
            }
            
            // Sauvegarder le nouveau fichier audio
            $fileName = strtoupper($request->letter) . '.mp3';
            $request->file('audio')->move(public_path('audio/letters'), $fileName);
            $data['audio_path'] = 'audio/letters/' . $fileName;
        } elseif ($letter->isDirty('letter') && $letter->audio_path) {
            // Si la lettre a changé mais pas de nouveau fichier audio, renommer l'ancien fichier
            $oldPath = public_path($letter->audio_path);
            $newFileName = strtoupper($request->letter) . '.mp3';
            $newPath = public_path('audio/letters/' . $newFileName);
            
            if (File::exists($oldPath)) {
                File::move($oldPath, $newPath);
                $data['audio_path'] = 'audio/letters/' . $newFileName;
            }
        }

        $letter->update($data);

        return redirect()->route('admin.letters.index')->with('success', 'Lettre mise à jour avec succès');
    }

    public function destroy(Letter $letter)
    {
        if (Auth::user()->role !== 'teacher') {
            return redirect()->route('home')->with('error', 'Accès non autorisé');
        }

        // Supprimer le fichier audio associé
        if ($letter->audio_path) {
            $audioPath = public_path($letter->audio_path);
            if (File::exists($audioPath)) {
                File::delete($audioPath);
            }
        }
        
        $letter->delete();
        return redirect()->route('admin.letters.index')->with('success', 'Lettre supprimée avec succès');
    }
} 