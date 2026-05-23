<?php

namespace App\Http\Controllers\Agen;

use App\Http\Controllers\Controller;

class AgenController extends Controller
{
    public function index()
    {
        return view('agen.dashboard');
    }
}