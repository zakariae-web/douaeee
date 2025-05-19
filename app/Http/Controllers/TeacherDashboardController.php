<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PronunciationAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'teacher') {
            return redirect()->route('home')->with('error', 'Accès non autorisé');
        }

        $users = User::where('role', 'student')->withCount('pronunciationAttempts')->paginate(10);

        return view('admin.dashboard', compact('users'));
    }

    public function showUserAttempts($userId)
    {
        $user = User::findOrFail($userId);
        $attempts = $user->pronunciationAttempts()->latest()->paginate(10);

        return view('admin.attempts', compact('user', 'attempts'));
    }
}
