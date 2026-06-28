<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): RedirectResponse|View
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        return view('home');
    }
}
