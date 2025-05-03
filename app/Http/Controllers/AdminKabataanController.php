<?php

namespace App\Http\Controllers;

use App\Models\SkYouthForm;
use Illuminate\Http\Request;

class AdminKabataanController extends Controller
{
    public function index()
    {
        return view('admin.kabataan.list');
    }

    public function update(Request $request, $id)
{
    $kabataan = SkYouthForm::findOrFail($id);

    // Validation and update logic
    $kabataan->update($request->all());

    return redirect()->route('kabataan.index')->with('success', 'Kabataan info successfully updated!');
}

}
