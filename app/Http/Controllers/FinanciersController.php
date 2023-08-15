<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Financiadora;

class FinanciersController extends Controller
{
    public function index()
    {
        $financiadoras = Financiadora::all();
        return view('financiadoras.index', compact('financiadoras'));
    }
}

   

