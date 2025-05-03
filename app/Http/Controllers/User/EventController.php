<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        $newEvent = Event::where('is_seen', false)->latest()->first();
        return view('users.events', compact('events', 'newEvent'));
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);
        $eventUrl = route('users.events.show', ['id' => $id]); // Generate event URL
        return view('users.events.show', compact('event', 'eventUrl'));
    }
    
    public function showEvents()
    {
        // Mark events as seen
        Event::where('is_seen', false)->update(['is_seen' => true]);

        // Retrieve all events
        $events = Event::latest()->get();

        return view('users.events', compact('events'));
    }

}
