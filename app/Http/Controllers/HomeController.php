<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Submission;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $event = Event::with('halls')->first();
        $hall = $event?->halls->first();

        return view('pages.home', compact('event', 'hall'));
    }

    public function storeSubmission(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'hall_id' => 'required|exists:halls,id',
            'booth_id' => 'required|string|max:50',
            'phone_number' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'company_name' => 'nullable|string|max:255',
        ]);

        // Check if booth is already submitted for this event/hall
        $exists = Submission::where('event_id', $validated['event_id'])
            ->where('hall_id', $validated['hall_id'])
            ->where('booth_id', $validated['booth_id'])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['booth_id' => 'This booth has already been submitted for this event.'])
                ->withInput();
        }

        Submission::create([
            ...$validated,
            'booth_name' => $validated['booth_id'], // Use booth ID as name
            'status' => 'pending',
        ]);

        return redirect()->back()
            ->with('success', 'Your booth submission has been received successfully! We will contact you soon.');
    }
}
