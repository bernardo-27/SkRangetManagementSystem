<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;

class AdminAnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->get();
        return view('admin.announcement', compact('announcements'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('admin.announcement')->with('success', 'Announcement posted successfully!');

    }

    public function edit($id)
    {
        $announcements = Announcement::findOrFail($id);
        return view('admin.form.edit', compact('announcements'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
    ]);

    $announcements = Announcement::findOrFail($id);
    $announcements->update([
        'title' => $request->title,
        'content' => $request->content,
    ]);

    return redirect()->route('admin.announcement')->with('success', 'Announcement updated successfully!');

}


    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return redirect()->route('admin.announcement')->with('success', 'Announcement deleted successfully!');
    }

}
