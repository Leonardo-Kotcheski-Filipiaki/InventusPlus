<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function showLogin()
    {
        return Inertia::render("user/login", [
            'user' => new User(),
        ]);
    }

    public function authenticate(Request $request)
    {
        dd($request->all());
    }
}
