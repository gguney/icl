<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fixture extends BaseModel
{
    protected $table = 'fixtures';
    protected $appends = ['is_played'];

    /**
     * @return bool
     */
    public function getIsPlayedAttribute(): bool
    {
        return !is_null($this->home_team_score);
    }

    /**
     * @return BelongsTo
     */
    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return BelongsTo
     */
    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return BelongsTo
     */
    public function stadium(): BelongsTo
    {
        return $this->belongsTo(Stadium::class);
    }

    /**
     * @param  Builder  $query
     * @return void
     */
    public function scopePlayed(Builder $query)
    {
        $query->whereNotNull('home_team_score');
    }

    /**
     * @param  Builder  $query
     * @return void
     */
    public function scopeNotPlayed(Builder $query)
    {
        $query->whereNull('home_team_score');
    }
}
