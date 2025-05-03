<?php

namespace App\Http\Controllers;
use SimpleSoftwareIO\QrCode\Facades\QrCode; // Import QR Code package
use Illuminate\Support\Facades\File;

use App\Models\Announcement;


class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::all();
        $newAnnouncement = Announcement::where('is_seen', false)->latest()->first();

        // // Generate QR Code for each announcement
        // foreach ($announcements as $announcement) {
        //     $announcement->qr_code = QrCode::size(300)->generate(url('users/announcements/' . $announcement->id));
        // }

        return view('users.announcement', compact('announcements', 'newAnnouncement'));
    }
    public function showAnnouncements()
    {
        // Mark announcements as seen
        Announcement::where('is_seen', false)->update(['is_seen' => true]);

        // Retrieve all announcements
        $announcements = Announcement::latest()->get();

        return view('users.announcement', compact('announcements'));
    }

    public function show($id)
{
    $announcement = Announcement::findOrFail($id);
    return view('users.announcements.show', compact('announcement'));
}
}
