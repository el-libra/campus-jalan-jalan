<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Spot extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'short_description',
        'description',
        'latitude',
        'longitude',
        'address',
        'open_time',
        'close_time',
        'has_wifi',
        'has_toilet',
        'is_wheelchair_accessible',
        'is_active',
    ];

    protected $casts = [
        'has_wifi' => 'boolean',
        'has_toilet' => 'boolean',
        'is_wheelchair_accessible' => 'boolean',
        'is_active' => 'boolean',
        'open_time' => 'datetime:H:i',
        'close_time' => 'datetime:H:i',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(SpotPhoto::class);
    }

    public function tours(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class, 'tour_spots')
            ->withTimestamps()
            ->withPivot('order_index')
            ->orderBy('tour_spots.order_index');
    }
}
