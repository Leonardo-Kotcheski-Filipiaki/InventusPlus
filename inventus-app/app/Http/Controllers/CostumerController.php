<?php

namespace App\Http\Controllers;

use App\Models\Costumer;
use Inertia\Inertia;

class CostumerController extends Controller
{
    public function index()
    {
        return Inertia::render('costumer/index', [
            'costumers' => Costumer::all()
        ]);
    }

    
}
