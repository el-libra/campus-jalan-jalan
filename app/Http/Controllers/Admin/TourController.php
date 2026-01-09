<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Spot;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tours = Tour::query()
            ->withCount('spots')
            ->orderBy('name')
            ->get();

        return view('admin.tours.index', [
            'tours' => $tours,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $spots = Spot::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.tours.create', [
            'spots' => $spots,
            'tour' => new Tour(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'spot_ids' => ['nullable', 'array'],
            'spot_ids.*' => ['integer', 'exists:spots,id'],
            'order_index' => ['nullable', 'array'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $tour = Tour::create($validated);

        $this->syncTourSpots($tour, $request->input('spot_ids', []), $request->input('order_index', []));

        return redirect()
            ->route('admin.tours.index')
            ->with('status', 'Tur berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tour $tour)
    {
        return redirect()->route('admin.tours.edit', $tour);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tour $tour)
    {
        $spots = Spot::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $tour->load('spots');

        return view('admin.tours.edit', [
            'tour' => $tour,
            'spots' => $spots,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'spot_ids' => ['nullable', 'array'],
            'spot_ids.*' => ['integer', 'exists:spots,id'],
            'order_index' => ['nullable', 'array'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $tour->update($validated);

        $this->syncTourSpots($tour, $request->input('spot_ids', []), $request->input('order_index', []));

        return redirect()
            ->route('admin.tours.index')
            ->with('status', 'Tur berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tour $tour)
    {
        $tour->delete();

        return redirect()
            ->route('admin.tours.index')
            ->with('status', 'Tur berhasil dihapus.');
    }

    private function syncTourSpots(Tour $tour, array $spotIds, array $orderIndex): void
    {
        $syncData = [];

        foreach ($spotIds as $spotId) {
            $syncData[$spotId] = [
                'order_index' => isset($orderIndex[$spotId]) ? (int) $orderIndex[$spotId] : 0,
            ];
        }

        $tour->spots()->sync($syncData);
    }
}
