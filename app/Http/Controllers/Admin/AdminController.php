<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function properties()
    {
        $properties = Property::with('owner')->latest()->paginate(15);
        return view('admin.properties', compact('properties'));
    }

    public function approve(Property $property)
    {
        $property->update(['is_approved' => !$property->is_approved]);
        $status = $property->is_approved ? 'diapprove' : 'ditolak';
        return back()->with('success', "Properti berhasil {$status}.");
    }
}