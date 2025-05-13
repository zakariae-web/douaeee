<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Stage;


class Letter extends Model
{
    use HasFactory;

    protected $fillable = ['value', 'stage_id'];


    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }


}
