<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\Official;

class OfficialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
        public function index()
        {

            $officials = Official::all();
            return view('admin.officials.list', compact('officials'));
        }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:officials,name',
            'position' => 'required|string|max:255',
            'term_start' => ['required', 'date', function ($attribute, $value, $fail) {
                $termStartYear = date('Y', strtotime($value));
                $minYear = date('Y') - 3;

                if ($termStartYear < $minYear) {
                    $fail('The term start year must not be earlier than ' . $minYear . '.');
                }
            }],

            'term_end' => ['required', 'date', 'after:term_start', function ($attribute, $value, $fail) use ($request) {
                $start = \Carbon\Carbon::parse($request->term_start);
                $end = \Carbon\Carbon::parse($value);
                $diff = $start->diffInYears($end);
                if ($diff > 3) {
                    $fail('The term must not be longer than 3 years.');
                }
            }],

            'email' => 'nullable|email|max:255|unique:officials,email',
            'phone' => ['required', 'regex:/^09\d{9}$/', Rule::unique('officials')],
            'birthdate' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $birthdate = \Carbon\Carbon::parse($value);
                    $age = $birthdate->age;

                    if ($age < 15 || $age > 30) {
                        $fail('The age must be between 15 and 30 years old.');
                    }

                    if ($birthdate->year > now()->year) {
                        $fail('The birth year cannot be greater than the current year.');
                    }
                },
            ],
            'achievements' => 'nullable|string|max:1000',
            'photo' => 'required|nullable|image|mimes:jpg,png,jpeg|max:10240'
        ]);

        // Additional checks for duplicate phone/email (optional, since already validated)
        if ($request->phone && Official::where('phone', $request->phone)->exists()) {
            return back()->withErrors(['phone' => 'This phone number is already taken.'])->withInput();
        }

        if ($request->email && Official::where('email', $request->email)->exists()) {
            return back()->withErrors(['email' => 'This email is already taken.'])->withInput();
        }

        // Position restrictions
        $skKagawadCount = Official::where('position', 'SK Kagawad')->count();
        $skChairpersonExists = Official::where('position', 'SK Chairperson')->exists();
        $skSecretaryExists = Official::where('position', 'SK Secretary')->exists();
        $skTreasurerExists = Official::where('position', 'SK Treasurer')->exists();

        if ($request->position === 'SK Kagawad' && $skKagawadCount >= 7) {
            return back()->withErrors(['position' => 'A maximum of 7 SK Kagawad members are allowed.'])->withInput();
        }

        if ($request->position === 'SK Chairperson' && $skChairpersonExists) {
            return back()->withErrors(['position' => 'Only 1 SK Chairperson is allowed.'])->withInput();
        }

        if ($request->position === 'SK Secretary' && $skSecretaryExists) {
            return back()->withErrors(['position' => 'Only 1 SK Secretary is allowed.'])->withInput();
        }

        if ($request->position === 'SK Treasurer' && $skTreasurerExists) {
            return back()->withErrors(['position' => 'Only 1 SK Treasurer is allowed.'])->withInput();
        }

        // Prepare data
        $data = $request->except(['photo']);

        // Handle file upload
        if ($request->hasFile('photo')) {
            $fileName = time() . '.' . $request->photo->extension();
            $filePath = $request->photo->storeAs('uploads', $fileName, 'public');
            $data['photo'] = 'storage/' . $filePath;
        }

        // Insert data with error handling
        try {
            Official::create($data);
            return back()->with('success', 'Official added successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while adding the official. Please try again.']);
        }
    }







    /**
     * Display the specified resource.
     */
    public function show(Official $official)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $official = Official::findOrFail($id);
        return response()->json($official);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $official = Official::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:officials,name,' . $id,
            'position' => 'string|max:255',
            'term_start' => ['required', 'date', function ($attribute, $value, $fail) {
                $termStartYear = date('Y', strtotime($value));
                $minYear = date('Y') - 3;
                if ($termStartYear < $minYear) {
                    $fail('The term start year must not be earlier than ' . $minYear . '.');
                }
            }],
            'term_end' => ['required', 'date', 'after:term_start', function ( $value, $fail) use ($request) {
                if ($request->term_start) {
                    $termStartYear = date('Y', strtotime($request->term_start));
                    $termEndYear = date('Y', strtotime($value));
                    if ($termEndYear - $termStartYear > 6) {
                        $fail('The term end year must not be more than 6 years after the term start year.');
                    }
                }
            }],
            'email' => 'nullable|email|max:255|unique:officials,email,' . $id,
            'phone' => ['required', 'regex:/^09\d{9}$/', Rule::unique('officials')->ignore($id)],
            'birthdate' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $birthdate = \Carbon\Carbon::parse($value);
                    $age = $birthdate->age;

                    if ($age < 15 || $age > 30) {
                        $fail('The age must be between 15 and 30 years old.');
                    }

                    if ($birthdate->year > now()->year) {
                        $fail('The birth year cannot be greater than the current year.');
                    }
                },
            ],
            'achievements' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg|max:10240'
        ]);

        // Update fields except photo and position
        $official->update($request->except('photo', 'position'));

        // Handle photo update
        if ($request->hasFile('photo')) {
            if ($official->photo && file_exists(public_path($official->photo))) {
                unlink(public_path($official->photo));
            }

            $fileName = time() . '.' . $request->photo->extension();
            $request->photo->move(public_path('uploads'), $fileName);
            $official->photo = 'uploads/' . $fileName;
            $official->save();
        }

        return redirect()->back()->with('success', 'Official updated successfully!');
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $official = Official::findOrFail($id);

        // Delete associated image if it exists
        if ($official->photo && file_exists(public_path($official->photo))) {
            unlink(public_path($official->photo));
        }

        $official->delete();

        return redirect()->route('admin.officials.index')->with('success', 'Official deleted successfully!');
    }


}
