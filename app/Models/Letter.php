<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Stage;


class Letter extends Model
{
    use HasFactory;

    protected $fillable = ['letter', 'stage_id', 'audio_path'];


    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }


}
