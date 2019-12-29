<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    /**
     * @return HasMany
     */
    public function moves(): HasMany
    {
        return $this->hasMany(Move::class);
    }
}
