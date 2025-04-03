<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PronunciationAttempt extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'letter', 'success', 'attempted_word'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
