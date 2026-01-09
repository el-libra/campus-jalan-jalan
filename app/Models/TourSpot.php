<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourSpot extends Model
{
    protected $fillable = [
        'tour_id',
        'spot_id',
        'order_index',
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function spot(): BelongsTo
    {
        return $this->belongsTo(Spot::class);
    }
}
