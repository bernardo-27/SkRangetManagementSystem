<x-app-layout>
    <x-slot name="header">
        <h2 class="text-center text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Kabataan List') }}
        </h2>
    </x-slot>

    <div class="container mt-5">
        <!-- Search Input -->
        <div class="mb-4 d-flex justify-content-center">
            <input type="text" id="searchInput" onkeyup="searchByName()" class="form-control w-75 py-2 px-3 border-primary rounded" placeholder="Search by name...">
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover shadow-sm rounded border">
                <thead class="table-primary">
                    <tr>
                        <th scope="col">Picture</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Age</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kabataan as $youth)
                        <tr>
                            <td>
                                @if($youth->profile_picture)
                                    <img src="{{ asset('storage/' . $youth->profile_picture) }}" alt="Profile Picture" class="img-thumbnail" width="80">
                                @else
                                    <img src="{{ asset('images/default.png') }}" alt="Default Picture" class="img-thumbnail" width="80">
                                @endif
                            </td>
                            <td>
                                <a href="#" onclick="showKabataanDetails({{ $youth->id }})" class="text-primary fw-bold text-decoration-underline">
                                    {{ $youth->full_name }}
                                </a>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($youth->dob)->age }}</td>
                            <td>{{ $youth->gender }}</td>
                            <td>
                                @php
                                    $formattedPhone = preg_match('/^09\d{9}$/', $youth->phone)
                                        ? preg_replace('/^(\d{4})(\d{3})(\d{4})$/', '$1-$2-$3', $youth->phone)
                                        : $youth->phone;
                                @endphp
                                {{ $formattedPhone }}
                            </td>
                            <td>
                                <button onclick="showKabataanDetails({{ $youth->id }})" class="btn btn-info btn-sm text-white me-2">
                                    View Details
                                </button>
                                <button onclick="showEditModal({{ $youth->id }})" class="btn btn-warning btn-sm text-white me-2">
                                    Edit
                                </button>
                                <form action="{{ route('kabataan.destroy', $youth->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $kabataan->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- Modal for Viewing Details -->
    <div id="kabataanModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Kabataan Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12 mb-4 d-flex justify-content-center">
                            <img id="modalProfilePicture" src="" alt="Profile Picture" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        </div>

                        <div class="col-md-6">
                            <p><strong>Full Name:</strong> <span id="modalFullName" class="text-muted"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Age:</strong> <span id="modalAge" class="text-muted"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Date of Birth:</strong> <span id="modalDob" class="text-muted"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Gender:</strong> <span id="modalGender" class="text-muted"></span></p>
                        </div>

                        <div class="col-md-6">
                            <p><strong>Street / Zone:</strong> <span id="modalAddress" class="text-muted"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Phone:</strong> <span id="modalPhone" class="text-muted"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Email:</strong> <span id="modalEmail" class="text-muted"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Education:</strong> <span id="modalEducation" class="text-muted"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>School Name:</strong> <span id="modalSchoolName" class="text-muted"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Voter Status:</strong> <span id="modalVoterStatus" class="text-muted"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Youth Organization:</strong> <span id="modalYouthOrg" class="text-muted"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Skills:</strong> <span id="modalSkills" class="text-muted"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Volunteer:</strong> <span id="modalVolunteer" class="text-muted"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Guardian Name:</strong> <span id="modalGuardianName" class="text-muted"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Guardian Contact:</strong> <span id="modalGuardianContact" class="text-muted"></span></p>
                        </div>
                        <div class="row">
                        <div class="col-md-6">
                            <p><strong>Valid ID:</strong></p>
                            <img id="modalNationalId" src="" alt="Valid ID" class="img-fluid rounded border" style="max-height: 300px;">
                        </div>
                        <div class="col-md-6">
                            <p><strong>Voter ID:</strong></p>
                            <img id="modalVoterId" src="" alt="Voter ID" class="img-fluid rounded border" style="max-height: 300px;">
                        </div>
                    </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal()">Close</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for Editing Record -->
    <div id="editKabataanModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Edit Kabataan Record</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="closeEditModal()"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="editId">
                        <div class="row g-3">
                            <div class="col-md-12 text-center mb-4 position-relative">
                                <!-- Profile picture preview container - making the entire image clickable -->
                                <label for="editProfilePicture" style="cursor: pointer;" data-bs-toggle="tooltip" title="Upload Profile Picture">
                                    <img id="editProfilePreview" src="" alt="Profile Preview" class="img-fluid rounded-circle"
                                         style="width: 150px; height: 150px; object-fit: cover; border: 2px solid #eee;">

                                    <!-- Hidden file input -->
                                    <input type="file" id="editProfilePicture" name="profile_picture" class="d-none" accept="image/*">
                                </label>
                            </div>

                            <!-- JavaScript to handle image preview -->
                            <script>
                            document.getElementById('editProfilePicture').addEventListener('change', function(event) {
                                const file = event.target.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        document.getElementById('editProfilePreview').src = e.target.result;
                                    }
                                    reader.readAsDataURL(file);
                                }
                            });
                            </script>

                            <div class="col-md-6">
                                <label for="editFullName" class="form-label">Full Name:</label>
                                <input type="text" id="editFullName" name="full_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editAge" class="form-label">Age:</label>
                                <input type="number" id="editAge" name="age" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="editDob" class="form-label">Date of Birth:</label>
                                <input type="date" id="editDob" name="dob" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editGender" class="form-label">Gender:</label>
                                <select id="editGender" name="gender" class="form-select" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="editAddress" class="form-label">Zone/Street:</label>
                                <input type="text" id="editAddress" name="address" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editPhone" class="form-label">Phone:</label>
                                <input type="text" id="editPhone" name="phone" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editEmail" class="form-label">Email:</label>
                                <input type="email" id="editEmail" name="email" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editEducation" class="form-label">Education:</label>
                                <input type="text" id="editEducation" name="education" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editSchoolName" class="form-label">School Name:</label>
                                <input type="text" id="editSchoolName" name="school_name" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="editVoterStatus" class="form-label">Voter Status:</label>
                                <input type="text" id="editVoterStatus" name="voter_status" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editYouthOrg" class="form-label">Youth Organization:</label>
                                <input type="text" id="editYouthOrg" name="youth_org" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editSkills" class="form-label">Skills:</label>
                                <input type="text" id="editSkills" name="skills" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="editVolunteer" class="form-label">Volunteer:</label>
                                <input type="text" id="editVolunteer" name="volunteer" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editGuardianName" class="form-label">Guardian Name:</label>
                                <input type="text" id="editGuardianName" name="guardian_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editGuardianContact" class="form-label">Guardian Contact:</label>
                                <input type="text" id="editGuardianContact" name="guardian_contact" class="form-control" required>
                            </div>

                            <div class="row">
                            <!-- National ID Upload -->
<div class="col-md-6">
    <label for="editNationalId" class="form-label">Valid ID:</label>
    <input type="file" id="editNationalId" name="national_id" class="form-control" accept="image/*">
    <img id="editNationalPreview" src="" alt="Valid ID" class="img-fluid mt-2 rounded border" style="max-height: 200px;">
</div>

<!-- Voter ID Upload -->
<div class="col-md-6">
    <label for="editVoterId" class="form-label">Voter ID:</label>
    <input type="file" id="editVoterId" name="voter_id" class="form-control" accept="image/*">
    <img id="editVoterPreview" src="" alt="Voter ID" class="img-fluid mt-2 rounded border" style="max-height: 200px;">
</div>
</div>

                        </div>
                        <div class="mt-4 d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <script>
// Update the DOMContentLoaded event to include this code
document.addEventListener('DOMContentLoaded', function () {
    // Handle date of birth changes to auto-calculate age
    const dobInput = document.getElementById('editDob');
    if (dobInput) {
        dobInput.addEventListener('change', function() {
            const dob = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }

            document.getElementById('editAge').value = age;
        });
    }

    // Image preview for profile picture
    const profileInput = document.getElementById('editProfilePicture');
    if (profileInput) {
        profileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('editProfilePreview').src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    // Image preview for national ID
    const nationalIdInput = document.getElementById('editNationalId');
    if (nationalIdInput) {
        nationalIdInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('editNationalPreview').src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    // Image preview for voter ID
    const voterIdInput = document.getElementById('editVoterId');
    if (voterIdInput) {
        voterIdInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('editVoterPreview').src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    // Handle delete buttons
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function (e) {
        e.preventDefault(); // Prevent the default form submit

        const form = this.closest('form');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to undo this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading indicator after confirmation
                Swal.fire({
                    title: 'Deleting...',
                    html: 'Please wait while we process your request.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Submit the form
                form.submit();
            }
        });
    });
});
});

// Fetch and display Kabataan details
function showKabataanDetails(id) {
    // Show loading dialog first
    Swal.fire({
        title: 'Loading Details',
        html: 'Please wait while we retrieve the information...',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(`/admin/kabataan/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Close loading dialog
            Swal.close();

            document.getElementById('modalFullName').textContent = data.full_name;
            document.getElementById('modalAge').textContent = data.age;
            document.getElementById('modalDob').textContent = formatDate(data.dob);
            document.getElementById('modalGender').textContent = data.gender;
            document.getElementById('modalAddress').textContent = data.address;
            document.getElementById('modalPhone').textContent = formatPhone(data.phone) || 'N/A';
            document.getElementById('modalEmail').textContent = data.email;
            document.getElementById('modalEducation').textContent = data.education;
            document.getElementById('modalSchoolName').textContent = data.school_name || 'N/A';
            document.getElementById('modalVoterStatus').textContent = data.voter_status;
            document.getElementById('modalYouthOrg').textContent = data.youth_org;
            document.getElementById('modalSkills').textContent = data.skills || 'N/A';
            document.getElementById('modalVolunteer').textContent = data.volunteer;
            document.getElementById('modalGuardianName').textContent = data.guardian_name;
            document.getElementById('modalGuardianContact').textContent = data.guardian_contact;

            // Set profile picture or fallback image
            const profilePicture = data.profile_picture ? `/storage/${data.profile_picture}` : '/images/default.jpg';
            document.getElementById('modalProfilePicture').src = profilePicture;

            const nationalIdImage = data.national_id ? `/storage/${data.national_id}` : '/images/default-id.png';
            document.getElementById('modalNationalId').src = nationalIdImage;

            const voterIdImage = data.voter_id ? `/storage/${data.voter_id}` : '/images/default-voter-id.png';
            document.getElementById('modalVoterId').src = voterIdImage;

            // Show the modal
            const kabataanModal = new bootstrap.Modal(document.getElementById('kabataanModal'));
            kabataanModal.show();
        })
        .catch(error => {
            // Close loading dialog and show error
            Swal.close();
            console.error('Error fetching details:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load details. Please try again.',
            });
        });
}

// Function to format phone number
function formatPhone(phone) {
    if (!phone) return '';
    return phone.replace(/^(\d{4})(\d{3})(\d{4})$/, '$1-$2-$3');
}

// Function to format date
function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' });
}

// Open the Edit Modal and Populate Data
function showEditModal(id) {
    // Show loading dialog first
    Swal.fire({
        title: 'Loading Record',
        html: 'Please wait while we retrieve the information...',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(`/admin/kabataan/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Close loading dialog
            Swal.close();

            document.getElementById('editId').value = id;
            document.getElementById('editFullName').value = data.full_name;
            document.getElementById('editAge').value = data.age;
            document.getElementById('editDob').value = data.dob;
            document.getElementById('editGender').value = data.gender;
            document.getElementById('editAddress').value = data.address;
            document.getElementById('editPhone').value = data.phone || '';
            document.getElementById('editEmail').value = data.email;
            document.getElementById('editEducation').value = data.education;
            document.getElementById('editSchoolName').value = data.school_name || '';
            document.getElementById('editVoterStatus').value = data.voter_status;
            document.getElementById('editYouthOrg').value = data.youth_org;
            document.getElementById('editSkills').value = data.skills || '';
            document.getElementById('editVolunteer').value = data.volunteer;
            document.getElementById('editGuardianName').value = data.guardian_name;
            document.getElementById('editGuardianContact').value = data.guardian_contact;

            // Set image previews (file inputs cannot be pre-filled)
            const profilePicture = data.profile_picture ? `/storage/${data.profile_picture}` : '/images/default.jpg';
            document.getElementById('editProfilePreview').src = profilePicture;

            const nationalId = data.national_id ? `/storage/${data.national_id}` : '/images/default-id.png';
            document.getElementById('editNationalPreview').src = nationalId;

            const voterId = data.voter_id ? `/storage/${data.voter_id}` : '/images/default-voter-id.png';
            document.getElementById('editVoterPreview').src = voterId;

            // Show modal
            const editModal = new bootstrap.Modal(document.getElementById('editKabataanModal'));
            editModal.show();
        })
        .catch(error => {
            // Close loading dialog and show error
            Swal.close();
            console.error('Error loading details:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load details. Please try again.',
            });
        });
}

// Handle Form Submission for Updating Record
document.getElementById('editForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const id = document.getElementById('editId').value;
    const formData = new FormData(this);

    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formData.append('_token', csrfToken);

    formData.append('_method', 'POST');

    Swal.fire({
        title: 'Updating...',
        text: 'Please wait while your changes are being saved.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(`/admin/kabataan/${id}/update`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();

        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Record updated successfully!',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                closeEditModal();
                window.location.reload();
            });
        } else {
            // Show validation errors if available
            let errorMessages = '';
            if (data.errors) {
                for (let field in data.errors) {
                    errorMessages += `${data.errors[field].join(', ')}\n`;
                }
            }
            Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                text: errorMessages || 'An error occurred while updating the record.',
                confirmButtonColor: '#d33'
            });
        }
    })
    .catch(error => {
        Swal.close();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Something went wrong while sending the request.',
            confirmButtonColor: '#d33'
        });
        console.error('Error:', error);
    });
});


document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Close the Edit Modal
function closeEditModal() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('editKabataanModal'));
    if (modal) modal.hide();
}

// Close the Details Modal
function closeModal() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('kabataanModal'));
    if (modal) modal.hide();
}
    </script>
<script>
    function searchByName() {
    const input = document.getElementById("searchInput");
    const filter = input.value.toLowerCase();
    const table = document.querySelector("table");
    const rows = table.querySelectorAll("tbody tr");

    rows.forEach(row => {
        const nameCell = row.querySelectorAll("td")[1];
        const name = nameCell.textContent || nameCell.innerText;

        if (name.toLowerCase().includes(filter)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}

// Optional: Update URL with search query to preserve search state on page reload or pagination
document.getElementById("searchInput").addEventListener("input", function() {
    const searchQuery = this.value;
    const url = new URL(window.location.href);
    if (searchQuery) {
        url.searchParams.set("search", searchQuery);
    } else {
        url.searchParams.delete("search");
    }
    window.history.replaceState({}, "", url);
});

</script>

</x-app-layout>
