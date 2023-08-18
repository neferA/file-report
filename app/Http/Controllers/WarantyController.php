<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoGarantia;

class WarantyController extends Controller
{
    public function index()
    {
        $tiposGarantias = TipoGarantia::all();

        return view('garantias.index', compact('tiposGarantia'));
    }
}
