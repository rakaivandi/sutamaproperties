<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::approved()->available()->with('cover');

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->q.'%')
                  ->orWhere('address', 'like', '%'.$request->q.'%')
                  ->orWhere('city', 'like', '%'.$request->q.'%');
            });
        }

        if ($request->filled('type'))      $query->where('type', $request->type);
        if ($request->filled('status'))    $query->where('status', $request->status);
        if ($request->filled('city'))      $query->where('city', 'like', '%'.$request->city.'%');
        if ($request->filled('price_min')) $query->where('price', '>=', $request->price_min);
        if ($request->filled('price_max')) $query->where('price', '<=', $request->price_max);
        if ($request->filled('bedrooms'))  $query->where('bedrooms', '>=', $request->bedrooms);

        match ($request->get('sort', 'latest')) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default      => $query->latest(),
        };

        $properties = $query->paginate(12)->withQueryString();
        $cities = Property::approved()->available()->distinct()->pluck('city')->sort()->values();

        return view('properties.index', compact('properties', 'cities'));
    }

    public function show(Property $property)
    {
        abort_if(!$property->is_approved, 404);
        $property->load('media', 'owner');

        $related = Property::approved()->available()
            ->where('id', '!=', $property->id)
            ->where('city', $property->city)
            ->with('cover')->limit(3)->get();

        return view('properties.show', compact('property', 'related'));
    }
}
