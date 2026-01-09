<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpotPhoto extends Model
{
    protected $fillable = [
        'spot_id',
        'photo_path',
    ];

    public function spot(): BelongsTo
    {
        return $this->belongsTo(Spot::class);
    }
}
