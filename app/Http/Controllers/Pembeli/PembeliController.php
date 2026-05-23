<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;

class PembeliController extends Controller
{
    public function index()
    {
        return view('pembeli.dashboard');
    }
}