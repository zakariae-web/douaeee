<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Services\UserProgressionService;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'xp',
        'level',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'xp' => 'integer',
            'level' => 'integer'
        ];
    }

    public function pronunciationAttempts()
    {
        return $this->hasMany(PronunciationAttempt::class);
    }

    public function addXp(bool $isWord = false): array
    {
        $progressionService = app(UserProgressionService::class);
        return $progressionService->addXp($this, $isWord);
    }

    public function getProgressionInfo(): array
    {
        $progressionService = app(UserProgressionService::class);
        return $progressionService->getProgressionInfo($this);
    }
}
