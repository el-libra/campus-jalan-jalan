<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index()
    {
        $tours = Tour::query()
            ->with([
                'spots' => function ($query) {
                    $query->where('is_active', true);
                },
                'spots.category',
                'spots.photos',
            ])
            ->orderBy('name')
            ->get();

        return view('tour.index', [
            'tours' => $tours,
        ]);
    }
}
