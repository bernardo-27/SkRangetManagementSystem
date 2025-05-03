<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $document = Document::all();
        return view('admin.documents.list', compact('document'));
    }

    public function create()
    {
        return view('admin.documents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|nullable|array',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $imagePaths = [];

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $path = $image->store('documents', 'public');
                $imagePaths[] = $path;
            }
        }

        Document::create([
            'title' => $request->title,
            'image' => json_encode($imagePaths),
        ]);

        return redirect()->route('admin.documents.list')->with('success', 'Document created successfully!');
    }


    public function edit($id)
    {
        $document = Document::findOrFail($id);
        return view('admin.documents.edit', compact('document'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|array',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $document = Document::findOrFail($id);
        $document->title = $request->title;

        $imagePaths = json_decode($document->image, true) ?? [];

        if ($request->hasFile('image')) {
            // Delete old images
            foreach ($imagePaths as $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }

            $newImagePaths = [];
            foreach ($request->file('image') as $image) {
                $path = $image->store('documents', 'public');
                $newImagePaths[] = $path;
            }

            $document->image = json_encode($newImagePaths);
        }

        $document->save();

        return redirect()->route('admin.documents.list')->with('success', 'Document updated successfully!');
    }


    public function destroy(Document $documents)
    {
        \Log::info('Document to delete:', ['document' => $documents]);

        // Delete associated images
        if ($documents->images) {
            foreach ($documents->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        // Delete the document record
        $documents->delete();

        \Log::info('Document deleted:', ['document_id' => $documents->id]);

        return redirect()->route('admin.documents.list')->with('success', 'Document deleted successfully!');
    }

}
