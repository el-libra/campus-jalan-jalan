<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tour extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function spots(): BelongsToMany
    {
        return $this->belongsToMany(Spot::class, 'tour_spots')
            ->withTimestamps()
            ->withPivot('order_index')
            ->orderBy('tour_spots.order_index');
    }
}
