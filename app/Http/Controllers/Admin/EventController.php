<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return view('admin.events.list', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $imagePaths = [];

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $path = $image->store('events', 'public');
                $imagePaths[] = $path;
            }
        }

        Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => json_encode($imagePaths),
        ]);

        return redirect()->route('admin.events.list')->with('success', 'Event created successfully!');
    }


    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $event = Event::findOrFail($id);
        $event->title = $request->title;
        $event->description = $request->description;

        $imagePaths = json_decode($event->image, true) ?? [];

        if ($request->hasFile('image')) {
            // Delete old images
            foreach ($imagePaths as $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }

            $newImagePaths = [];
            foreach ($request->file('image') as $image) {
                $path = $image->store('events', 'public');
                $newImagePaths[] = $path;
            }

            $event->image = json_encode($newImagePaths);
        }

        $event->save();

        return redirect()->route('admin.events.list')->with('success', 'Event updated successfully!');
    }


    public function destroy(Event $event)
    {
        \Log::info('Event to delete:', ['event' => $event]);

        // Delete associated images
        if ($event->images) {
            foreach ($event->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        // Delete the event record
        $event->delete();

        \Log::info('Event deleted:', ['event_id' => $event->id]);

        return redirect()->route('admin.events.list')->with('success', 'Event deleted successfully!');
    }



}
