<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Game
 *
 * @package App
 *
 * @method HasMany player1Wins
 * @method HasMany player2Wins
 * @method HasMany ties
 */
class Game extends Model
{
    /**
     * @return HasMany
     */
    public function moves(): HasMany
    {
        return $this->hasMany(Move::class);
    }

    /**
     * @return HasMany
     */
    public function scopePlayer1Wins(): HasMany
    {
        return $this->moves()->where('winner', '1');
    }

    /**
     * @return HasMany
     */
    public function scopePlayer2Wins(): HasMany
    {
        return $this->moves()->where('winner', '-1');
    }

    /**
     * @return HasMany
     */
    public function scopeTies(): HasMany
    {
        return $this->moves()->where('winner', '0');
    }
}
