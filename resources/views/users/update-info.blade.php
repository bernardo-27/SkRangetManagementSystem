<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <x-slot name="header">
        <div class="py-4 px-6 bg-white shadow-sm rounded-lg">
            <h2 class="text-2xl font-bold text-center text-indigo-700">Update Youth Information</h2>
            <p class="text-center text-gray-600 mt-2">Keep your profile updated for better engagement with SK programs</p>
        </div>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-10  mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="POST" action="{{ route('users.update-info', $kabataan->id) }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')

                            <!-- Personal Information Section -->
                            <div class="mb-4">

                                <!-- Profile Picture Upload Section -->
                                <div class="mb-4 text-center">
                                    <div class="row justify-content-center align-items-center">
                                        <!-- Profile Picture Preview -->
                                        <div class="col-auto position-relative" data-bs-toggle="tooltip" data-bs-placement="top" title="Click to change profile picture">
                                            <label for="profile_picture" style="cursor: pointer;">
                                                @if($kabataan->profile_picture)
                                                    <img id="imagePreview" class="rounded-circle border border-secondary" src="{{ asset('storage/' . $kabataan->profile_picture) }}" alt="Profile Picture" style="width: 96px; height: 96px;">
                                                @else
                                                    <div id="imagePreview" class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" style="width: 15px; height: 150px;">
                                                        <i class="bi bi-person-fill" style="font-size: 60px; color: #ccc;"></i>
                                                    </div>
                                                @endif
                                            </label>
                                        </div>
                                        <!-- Hidden File Input -->
                                        <div class="col-auto">
                                            <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="d-none" onchange="previewImage(event)">
                                        </div>
                                        <h5 class="text-primary mb-3">
                                            <i class="bi bi-image-fill me-2"></i> Profile Picture
                                        </h5>
                                    </div>
                                </div>

                                <h5 class="text-primary mb-3">
                                    <i class="bi bi-person-fill me-2"></i> Personal Information
                                </h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="full_name" class="form-label">Full Name</label>
                                        <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $kabataan->full_name) }}" class="form-control" required>
                                        @error('full_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="dob" class="form-label">Date of Birth</label>
                                        <input type="date" id="dob" name="dob" value="{{ old('dob', $kabataan->dob) }}" class="form-control" required>
                                        @error('dob')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select id="gender" name="gender" class="form-select" required>
                                            <option value="">Select Gender</option>
                                            <option value="male" {{ $kabataan->gender == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ $kabataan->gender == 'female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                        @error('gender')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="national_id" class="form-label">Valid ID Picture</label>
                                        <input type="file" id="national_id" name="national_id" accept="image/*" class="form-control">
@if ($kabataan->national_id)
    <div class="mt-2">
        <img src="{{ asset('storage/' . $kabataan->national_id) }}" alt="Valid ID" class="img-thumbnail" style="max-width: 200px;">
    </div>
@endif

                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information Section -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="bi bi-envelope-fill me-2"></i> Contact Information
                                </h5>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="address" class="form-label">Permanent Address</label>
                                        <input type="text" id="address" name="address" value="{{ old('address', $kabataan->address) }}" class="form-control" required>
                                        @error('address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $kabataan->phone) }}" class="form-control" required>
                                        @error('phone')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" id="email" name="email" value="{{ old('email', $kabataan->email) }}" class="form-control">
                                        @error('email')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Educational Background Section -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="bi bi-mortarboard-fill me-2"></i> Educational Background
                                </h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="education" class="form-label">Current Education Level</label>
                                        <select id="education" name="education" class="form-select" required>
                                            <option value="">Select Level</option>
                                            <option value="high_school" {{ $kabataan->education == 'high_school' ? 'selected' : '' }}>High School</option>
                                            <option value="college" {{ $kabataan->education == 'college' ? 'selected' : '' }}>College</option>
                                            <option value="vocational" {{ $kabataan->education == 'vocational' ? 'selected' : '' }}>Vocational</option>
                                            <option value="out_of_school" {{ $kabataan->education == 'out_of_school' ? 'selected' : '' }}>Out-of-School Youth</option>
                                        </select>
                                        @error('education')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="school_name" class="form-label">School Name (if applicable)</label>
                                        <input type="text" id="school_name" name="school_name" value="{{ old('school_name', $kabataan->school_name) }}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <!-- Parent/Guardian Information Section -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="bi bi-person-lines-fill me-2"></i> Parent/Guardian Information
                                </h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="guardian_name" class="form-label">Parent/Guardian Name</label>
                                        <input type="text" id="guardian_name" name="guardian_name" value="{{ old('guardian_name', $kabataan->guardian_name) }}" class="form-control" required>
                                        @error('guardian_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="guardian_contact" class="form-label">Parent/Guardian Contact No.</label>
                                        <input type="tel" id="guardian_contact" name="guardian_contact" value="{{ old('guardian_contact', $kabataan->guardian_contact) }}" class="form-control" required>
                                        @error('guardian_contact')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- SK Eligibility & Voter Status Section -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="bi bi-check-circle-fill me-2"></i> SK Eligibility & Voter Status
                                </h5>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Are you a registered voter in your barangay?</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="voter_status" id="voter_yes" value="yes" {{ $kabataan->voter_status == 'yes' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="voter_yes">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="voter_status" id="voter_no" value="no" {{ $kabataan->voter_status == 'no' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="voter_no">No</label>
                                        </div>
                                        @error('voter_status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <label for="voter_id" class="form-label">Voter's ID (if applicable)</label>
                                        <input type="file" id="voter_id" name="voter_id" accept="image/*" class="form-control">
                                        @if ($kabataan->voter_id)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $kabataan->voter_id) }}" alt="Voter ID" class="img-thumbnail" style="max-width: 200px;">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Community Involvement Section -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="bi bi-people-fill me-2"></i> Community Involvement
                                </h5>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Are you a member of any youth organizations?</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="youth_org" id="org_yes" value="yes" {{ $kabataan->youth_org == 'yes' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="org_yes">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="youth_org" id="org_no" value="no" {{ $kabataan->youth_org == 'no' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="org_no">No</label>
                                        </div>
                                        @error('youth_org')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    </div>
                                    <!-- Volunteer Opportunity Section -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="bi bi-hand-thumbs-up-fill me-2"></i> Volunteer Opportunity
                                </h5>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Would you like to volunteer for SK programs?</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="volunteer" id="volunteer_yes" value="yes" {{ $kabataan->volunteer == 'yes' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="volunteer_yes">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="volunteer" id="volunteer_no" value="no" {{ $kabataan->volunteer == 'no' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="volunteer_no">No</label>
                                        </div>
                                        @error('volunteer')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    </div>
                                </div>
                            </div>
                                    <div class="col-md-12">
                                        <label for="skills" class="form-label">Skills & Interests</label>
                                        <textarea id="skills" name="skills" rows="3" class="form-control">{{ old('skills', $kabataan->skills) }}</textarea>
                                    </div>
                                </div>
                            </div>






                            <!-- Form Submission -->
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Update Information</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


<script>
// Initialize Bootstrap tooltips
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    // Function to preview the selected image
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('imagePreview');
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    // Replace the "No Image" div with an image element
                    preview.outerHTML = `<img id="imagePreview" class="rounded-circle border border-secondary" src="${e.target.result}" alt="Profile Picture Preview" style="width: 96px; height: 96px;">`;
                }
            };
            reader.readAsDataURL(file);
        }
    }




// Add loading indicator when form is submitted
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form.needs-validation');

    if (form) {
        form.addEventListener('submit', function(event) {
            // Only show loading if form is valid
            if (form.checkValidity()) {
                Swal.fire({
                    title: 'Updating Profile',
                    html: 'Please wait while we update your information...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }
        });
    }
});

// SweetAlert2 for success/error messages
@if(session('success_message'))
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session("success_message") }}',
    });
@endif
@if(session('error_message'))
    let errorList = `{!! implode('<br>', session('error_message')) !!}`;
    Swal.fire({
        icon: 'error',
        title: 'Updating Profile Error',
        html: errorList,
    });
@endif
    </script>

</x-app-layout>

