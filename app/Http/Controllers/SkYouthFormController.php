<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SkYouthForm;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SkYouthFormController extends Controller {

// admin controller / for admin
public function index(Request $request)
{
    $kabataan = SkYouthForm::all()->map(function ($youth) {
        $youth->age = Carbon::parse($youth->dob)->age;
        return $youth;
    });


    $search = $request->input('search');
    $kabataan = SkYouthForm::when($search, function ($query, $search) {
        return $query->where('full_name', 'like', '%' . $search . '%');
    })
    ->paginate(15);


    return view('admin.kabataan.list', compact('kabataan'));
}

    public function show($id) {
        $kabataan = SkYouthForm::findOrFail($id);

        // Ensure correct whole number age calculation
        $kabataan->age = Carbon::parse($kabataan->dob)->age;

        return response()->json($kabataan);
    }

    public function edit($id) {
        $kabataan = SkYouthForm::findOrFail($id);
        return view('admin.kabataan.edit', compact('kabataan'));
    }

    public function update(Request $request, $id)
    {
        $kabataan = SkYouthForm::findOrFail($id);

        // Manually handle validation to return JSON
        $validator = Validator::make($request->all(), [
            'full_name' => [
                'required', 'string', 'max:255',
                Rule::unique('sk_youth_form', 'full_name')->ignore($kabataan->id),
            ],
            'age' => 'required|integer|min:1',
            'dob' => [
                'required', 'date',
                function ($attribute, $value, $fail) {
                    $dob = Carbon::parse($value);
                    $age = $dob->age;

                    if ($age < 15 || $age > 30) {
                        $fail('Age must be between 15 and 30 years old.');
                    }

                    if ($dob->isFuture()) {
                        $fail('Date of birth cannot be in the future.');
                    }
                },
            ],
            'gender' => 'required|string',
            'national_id' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'address' => 'required|string',
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('sk_youth_form', 'email')->ignore($kabataan->id),
            ],
            'phone' => [
                'required', 'regex:/^09\d{9}$/',
                Rule::unique('sk_youth_form', 'phone')->ignore($kabataan->id),
            ],
            'education' => 'required|string',
            'school_name' => 'nullable|string|max:255',
            'voter_status' => 'required|string',
            'voter_id' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'youth_org' => 'required|string',
            'skills' => 'nullable|string|max:255',
            'volunteer' => 'required|string',
            'guardian_name' => 'required|string|max:255',
            'guardian_contact' => 'required|string|max:15',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        if ($validator->fails()) {
            \Log::error($validator->errors()->all());
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        // Handle profile picture update
        if ($request->hasFile('profile_picture')) {
            // Delete old image if it exists
            if ($kabataan->profile_picture && Storage::disk('public')->exists($kabataan->profile_picture)) {
                Storage::disk('public')->delete($kabataan->profile_picture);
            }

            $fileName = time() . '_profile.' . $request->profile_picture->extension();
            $filePath = $request->file('profile_picture')->storeAs('profile_pictures', $fileName, 'public');
            $data['profile_picture'] = $filePath;
        }

        // Handle national ID update
        if ($request->hasFile('national_id')) {
            // Delete old image if it exists
            if ($kabataan->national_id && Storage::disk('public')->exists($kabataan->national_id)) {
                Storage::disk('public')->delete($kabataan->national_id);
            }

            $fileName = time() . '_national.' . $request->national_id->extension();
            $filePath = $request->file('national_id')->storeAs('national_ids', $fileName, 'public');
            $data['national_id'] = $filePath;
        }

        // Handle voter ID update
        if ($request->hasFile('voter_id')) {
            // Delete old image if it exists
            if ($kabataan->voter_id && Storage::disk('public')->exists($kabataan->voter_id)) {
                Storage::disk('public')->delete($kabataan->voter_id);
            }

            $fileName = time() . '_voter.' . $request->voter_id->extension();
            $filePath = $request->file('voter_id')->storeAs('voter_ids', $fileName, 'public');
            $data['voter_id'] = $filePath;
        }

        try {
            $kabataan->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Record updated successfully!',
            ]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'errors' => ['server' => ['Failed to update the record. Please try again.']],
            ], 500);
        }
    }

// user controller  / for user
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'full_name' => [
            'required', 'string', 'max:255',
            Rule::unique('sk_youth_form', 'full_name'),
        ],
        'age' => 'required|integer|min:1',
        'dob' => [
            'required', 'date',
            function ($attribute, $value, $fail) {
                try {
                    $dob = Carbon::parse($value);
                    if ($dob->isFuture()) {
                        return $fail('Date of birth cannot be in the future.');
                    }

                    $age = $dob->age;
                    if ($age < 15 || $age > 30) {
                        return $fail('Age must be between 15 and 30 years old.');
                    }
                } catch (\Exception $e) {
                    return $fail('Invalid date format for date of birth.');
                }
            },
        ],
        'gender' => 'required',
        'national_id' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
        'address' => 'required',
        'email' => [
            'required', 'email', 'max:255',
            Rule::unique('sk_youth_form', 'email'),
        ],
        'phone' => [
            'required', 'regex:/^09\d{9}$/',
            Rule::unique('sk_youth_form', 'phone'),
        ],
        'education' => 'required',
        'school_name' => 'nullable|string',
        'voter_status' => 'required',
        'voter_id' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        'youth_org' => 'required',
        'skills' => 'nullable|string',
        'volunteer' => 'required',
        'guardian_name' => 'required',
        'guardian_contact' => 'required',
        'profile_picture' => 'nullable|image|max:10240',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }


    $data = $request->all();
    $data['user_id'] = auth()->id();

    if ($request->hasFile('profile_picture')) {
        $imagePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        $data['profile_picture'] = $imagePath;
    }


    if ($request->hasFile('national_id')) {
        $nationalIdPath = $request->file('national_id')->store('national_ids', 'public');
        $data['national_id'] = $nationalIdPath;
    }

    if ($request->hasFile('voter_id')) {
        $voterIdPath = $request->file('voter_id')->store('voter_ids', 'public');
        $data['voter_id'] = $voterIdPath;
    }

    try {
        SkYouthForm::create($data);
        return redirect()->route('dashboard')->with('success', 'Registration successfully submitted!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Something went wrong. Please try again.')->withInput();
    }
}


    public function checkFormSubmission() {
        $userId = auth()->id();
        $isRegistered = SkYouthForm::where('user_id', $userId)->exists();

        if (!$isRegistered) {
            return view('registration-form', ['showModal' => true]); // Force modal if not registered
        }

        return redirect()->route('dashboard'); // Redirect if already registered
    }

    public function destroy($id) {
        $kabataan = SkYouthForm::findOrFail($id);
        $kabataan->delete();

        return redirect()->route('kabataan.index')->with('success', 'Record deleted successfully.');
    }
}
