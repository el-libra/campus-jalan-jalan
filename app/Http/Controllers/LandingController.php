<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Spot;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()
            ->orderBy('name')
            ->get();

        $spots = Spot::query()
            ->with(['category', 'photos'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('landing.index', [
            'categories' => $categories,
            'spots' => $spots,
        ]);
    }
}
