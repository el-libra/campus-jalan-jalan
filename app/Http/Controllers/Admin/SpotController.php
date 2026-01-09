<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Spot;
use App\Models\SpotPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SpotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $spots = Spot::query()
            ->with('category')
            ->orderBy('name')
            ->get();

        return view('admin.spots.index', [
            'spots' => $spots,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::query()->orderBy('name')->get();

        return view('admin.spots.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'address' => ['nullable', 'string', 'max:255'],
            'open_time' => ['nullable', 'date_format:H:i'],
            'close_time' => ['nullable', 'date_format:H:i'],
            'photos.*' => ['nullable', 'image', 'max:4096'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['has_wifi'] = $request->boolean('has_wifi');
        $validated['has_toilet'] = $request->boolean('has_toilet');
        $validated['is_wheelchair_accessible'] = $request->boolean('is_wheelchair_accessible');

        $spot = Spot::create($validated);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('spot-photos', 'public');
                SpotPhoto::create([
                    'spot_id' => $spot->id,
                    'photo_path' => $path,
                ]);
            }
        }

        return redirect()
            ->route('admin.spots.index')
            ->with('status', 'Spot berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Spot $spot)
    {
        return redirect()->route('admin.spots.edit', $spot);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Spot $spot)
    {
        $categories = Category::query()->orderBy('name')->get();
        $spot->load('photos');

        return view('admin.spots.edit', [
            'spot' => $spot,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Spot $spot)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'address' => ['nullable', 'string', 'max:255'],
            'open_time' => ['nullable', 'date_format:H:i'],
            'close_time' => ['nullable', 'date_format:H:i'],
            'photos.*' => ['nullable', 'image', 'max:4096'],
            'remove_photo_ids' => ['nullable', 'array'],
            'remove_photo_ids.*' => ['integer', 'exists:spot_photos,id'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['has_wifi'] = $request->boolean('has_wifi');
        $validated['has_toilet'] = $request->boolean('has_toilet');
        $validated['is_wheelchair_accessible'] = $request->boolean('is_wheelchair_accessible');

        $spot->update($validated);

        if (!empty($validated['remove_photo_ids'])) {
            $photos = SpotPhoto::query()
                ->where('spot_id', $spot->id)
                ->whereIn('id', $validated['remove_photo_ids'])
                ->get();

            foreach ($photos as $photo) {
                Storage::disk('public')->delete($photo->photo_path);
                $photo->delete();
            }
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('spot-photos', 'public');
                SpotPhoto::create([
                    'spot_id' => $spot->id,
                    'photo_path' => $path,
                ]);
            }
        }

        return redirect()
            ->route('admin.spots.index')
            ->with('status', 'Spot berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Spot $spot)
    {
        $spot->delete();

        return redirect()
            ->route('admin.spots.index')
            ->with('status', 'Spot berhasil dihapus.');
    }
}
