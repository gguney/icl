<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stadium extends BaseModel
{
    protected $table = 'stadiums';

    /**
     * @return BelongsTo
     */
    public function ownerTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
