<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Club extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class)->withTimestamps();
    }

    public function workingHours(): HasMany
    {
        return $this->hasMany(ClubWorkingHour::class);
    }
}
