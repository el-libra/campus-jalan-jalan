<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use Illuminate\Http\Request;

class SpotController extends Controller
{
    public function show(string $slug)
    {
        $spot = Spot::query()
            ->with(['category', 'photos'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedSpots = Spot::query()
            ->where('category_id', $spot->category_id)
            ->where('id', '!=', $spot->id)
            ->where('is_active', true)
            ->limit(6)
            ->get();

        return view('spots.show', [
            'spot' => $spot,
            'relatedSpots' => $relatedSpots,
        ]);
    }
}
