<?php

namespace App\Services;

use App\Models\User;

class UserProgressionService
{
    private const BASE_XP = 100;
    private const XP_FACTOR = 1.5;
    private const LETTER_XP = 10;
    private const WORD_XP = 25;

    public function calculateRequiredXp(int $level): int
    {
        return (int) (self::BASE_XP * pow($level, self::XP_FACTOR));
    }

    public function addXp(User $user, bool $isWord = false): array
    {
        $xpGained = $isWord ? self::WORD_XP : self::LETTER_XP;
        $oldLevel = $user->level;
        $oldXp = $user->xp;
        
        $user->xp += $xpGained;
        
        // Vérifier si l'utilisateur monte de niveau
        while ($user->xp >= $this->calculateRequiredXp($user->level)) {
            $user->level++;
        }
        
        $user->save();
        
        return [
            'xp_gained' => $xpGained,
            'old_level' => $oldLevel,
            'new_level' => $user->level,
            'old_xp' => $oldXp,
            'new_xp' => $user->xp,
            'next_level_xp' => $this->calculateRequiredXp($user->level),
            'leveled_up' => $oldLevel < $user->level
        ];
    }

    public function getProgressionInfo(User $user): array
    {
        $currentLevelXp = $this->calculateRequiredXp($user->level);
        $nextLevelXp = $this->calculateRequiredXp($user->level + 1);
        $xpForNextLevel = $nextLevelXp - $user->xp;
        
        return [
            'current_level' => $user->level,
            'current_xp' => $user->xp,
            'xp_for_next_level' => $xpForNextLevel,
            'progress_percentage' => min(100, ($user->xp / $currentLevelXp) * 100)
        ];
    }
} 