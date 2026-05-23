<?php

namespace App\Http\Controllers\Agen;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyMedia;
use Illuminate\Http\Request;

class AgenPropertyController extends Controller
{
    public function index()
    {
        $properties = Property::where('user_id', auth()->id())
            ->with('cover')->latest()->paginate(10);
        return view('agen.properties.index', compact('properties'));
    }

    public function create()
    {
        return view('agen.properties.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'type'          => 'required|in:rumah,apartemen,tanah,ruko,villa',
            'status'        => 'required|in:dijual,disewakan',
            'price'         => 'required|numeric|min:0',
            'price_monthly' => 'nullable|numeric',
            'city'          => 'required|string',
            'address'       => 'required|string',
            'bedrooms'      => 'nullable|integer|min:0',
            'bathrooms'     => 'nullable|integer|min:0',
            'land_area'     => 'nullable|integer|min:0',
            'building_area' => 'nullable|integer|min:0',
            'photos'        => 'nullable|array',
            'photos.*'      => 'image|max:2048',
        ]);

        $validated['user_id']     = auth()->id();
        $validated['is_approved'] = false;

        $property = Property::create($validated);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $i => $photo) {
                $path = $photo->store('properties', 'public');
                PropertyMedia::create([
                    'property_id' => $property->id,
                    'type'        => 'image',
                    'path'        => $path,
                    'is_cover'    => $i === 0,
                    'order'       => $i,
                ]);
            }
        }

        return redirect()->route('agen.properties.index')
            ->with('success', 'Properti berhasil ditambahkan. Menunggu approval admin.');
    }

    public function edit(Property $property)
    {
        abort_if($property->user_id !== auth()->id(), 403);
        return view('agen.properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        abort_if($property->user_id !== auth()->id(), 403);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'type'        => 'required|in:rumah,apartemen,tanah,ruko,villa',
            'status'      => 'required|in:dijual,disewakan',
            'price'       => 'required|numeric|min:0',
            'city'        => 'required|string',
            'address'     => 'required|string',
            'bedrooms'    => 'nullable|integer|min:0',
            'bathrooms'   => 'nullable|integer|min:0',
            'photos'      => 'nullable|array',
            'photos.*'    => 'image|max:2048',
        ]);

        $property->update($validated);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $i => $photo) {
                $path = $photo->store('properties', 'public');
                PropertyMedia::create([
                    'property_id' => $property->id,
                    'type'        => 'image',
                    'path'        => $path,
                    'is_cover'    => false,
                    'order'       => $property->media()->count() + $i,
                ]);
            }
        }

        return redirect()->route('agen.properties.index')
            ->with('success', 'Properti berhasil diupdate.');
    }

    public function destroy(Property $property)
    {
        abort_if($property->user_id !== auth()->id(), 403);
        $property->delete();
        return redirect()->route('agen.properties.index')
            ->with('success', 'Properti berhasil dihapus.');
    }
}