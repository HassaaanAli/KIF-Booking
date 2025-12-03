<?php

namespace App\Http\Controllers;

use App\Models\Event;

class HomeController extends Controller
{
    public function index()
    {
        $event = Event::with('halls')->first();
        $hall = $event?->halls->first();

        return view('pages.home', compact('event', 'hall'));
    }
}
