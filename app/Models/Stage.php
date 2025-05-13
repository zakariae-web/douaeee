<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Letter;
use App\Models\PronunciationAttempt;

class Stage extends Model
{
    // Définir les propriétés de la table si nécessaire
    protected $table = 'stages'; // Le nom de votre table, si elle diffère de "stages"
    
    // Définir les colonnes qui peuvent être assignées en masse
    protected $fillable = [
        'name', // Exemple de colonne : nom du stage
        'description', // Exemple de colonne : description du stage
        // Ajoutez d'autres colonnes selon votre structure de base de données
    ];

    // Relation : Un stage peut avoir plusieurs lettres
    public function letters()
    {
        return $this->hasMany(Letter::class, 'stage_id');
    }

    // Relation : Un stage peut avoir plusieurs tentatives
    public function attempts()
    {
        return $this->hasMany(PronunciationAttempt::class, 'stage_id');
    }

    // Si vous avez un attribut "timestamps" dans votre table
    public $timestamps = true;
}
