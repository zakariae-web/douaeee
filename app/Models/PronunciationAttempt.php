<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PronunciationAttempt extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'letter', 'attempted_word', 'success', 'skipped'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
