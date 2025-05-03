<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SkYouthForm;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class KabataanController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */


    public function edit($id)
    {
        // Find the SkYouthForm record by user_id
        $kabataan = SkYouthForm::where('user_id', $id)->firstOrFail();
        return view('users.update-info', compact('kabataan'));
    }
    /**
     * Update the specified resource in storage.
     */


     public function update(Request $request, $id)
     {
         $kabataan = SkYouthForm::findOrFail($id);

         $validator = Validator::make($request->all(), [
             'full_name' => [
                 'required', 'string', 'max:255',
                 Rule::unique('sk_youth_form', 'full_name')->ignore($kabataan->id),
             ],
'dob' => [
    'required', 'date',
    function ($attribute, $value, $fail) {
        try {
            $dob = Carbon::parse($value);
            $age = $dob->age;

            if ($age < 15 || $age > 30) {
                $fail('Age must be between 15 and 30 years old.');
            }

            if ($dob->year > now()->year) {
                $fail('Date of birth cannot be in the future.');
            }
        } catch (\Exception $e) {
            $fail('Invalid date format.');
        }
    }
],

             'gender' => 'required|string',
            'national_id' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
             'address' => 'required|string',
             'phone' => [
                 'required', 'regex:/^\d{11}$/',
                 Rule::unique('sk_youth_form', 'phone')->ignore($kabataan->id),
             ],
             'email' => [
                 'nullable', 'email', 'max:255',
                 Rule::unique('sk_youth_form', 'email')->ignore($kabataan->id),
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
             'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
         ]);

         if ($validator->fails()) {
             return redirect()->back()
                 ->withErrors($validator)
                 ->withInput()
                 ->with('error_message', $validator->errors()->all());
         }

         $validated = $validator->validated();

         try {
             $kabataan->update($validated);


             if ($request->hasFile('national_id')) {
                if ($kabataan->national_id) {
                    Storage::delete('public/' . $kabataan->national_id);
                }

                $nationalIdPath = $request->file('national_id')->store('national_ids', 'public');
                $kabataan->national_id = $nationalIdPath;
                $kabataan->save();
            }


             if ($request->hasFile('voter_id')) {
                if ($kabataan->voter_id) {
                    Storage::delete('public/' . $kabataan->voter_id);
                }

                $voterIdPath = $request->file('voter_id')->store('voter_ids', 'public');
                $kabataan->voter_id = $voterIdPath;
                $kabataan->save();
            }


             if ($request->hasFile('profile_picture')) {
                 if ($kabataan->profile_picture) {
                     Storage::delete('public/' . $kabataan->profile_picture);
                 }

                 $path = $request->file('profile_picture')->store('profile_pictures', 'public');
                 $kabataan->profile_picture = $path;
                 $kabataan->save();
             }

             return redirect()->back()->with('success_message', 'Youth information updated successfully!');
         } catch (\Exception $e) {
             return redirect()->back()->with('error_message', ['Something went wrong. Please try again.']);
         }
     }

}
