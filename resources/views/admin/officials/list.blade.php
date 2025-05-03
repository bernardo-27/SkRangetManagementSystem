<x-app-layout>
    <link rel="icon" href="/images/sk-ranget.png">


    <x-slot name="header">
        <h2 class="text-center text-primary fw-bold">Officials List</h2>
    </x-slot>

    <div class="container py-5">
        <div class="card shadow-xxl p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="text-secondary">List of Officials</h4>

                {{-- hide the button after reaching 10 Officials include SK chairperson. --}}
                @if ($officials->count() < 10)
                    <button id="openModal" class="btn btn-primary">Add Official</button>
                @endif
            </div>

            <!-- Success/Error Message -->
            {{-- @if(session('success'))
                <div id="successMessage" class="alert alert-success position-absolute top-0 start-50 translate-middle-x mt-3">
                    {{ session('success') }}
                </div>
            @endif --}}

            {{-- @if($errors->any())
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger position-absolute top-0 start-50 translate-middle-x mt-3 errorMessage">
                        {{ $error }}
                    </div>
                @endforeach
            @endif --}}



            <!-- Officials Table -->
            <div class="table-responsive">
                <table class="table table-hover table-bordered text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Birthdate</th>
                            <th>Achievements</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th>Term Start</th>
                            <th>Term End</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($officials as $official)
                        <tr>
                            <td>
                                @if($official->photo)
                                    <img src="{{ asset($official->photo) }}" alt="Photo" class="rounded" width="70">
                                @else
                                    No Image
                                @endif
                            </td>
                            <td>{{ $official->name }}</td>
                            <td>
                                @php
                                    $formattedPhone = preg_match('/^09\d{9}$/', $official->phone)
                                        ? preg_replace('/^(\d{4})(\d{3})(\d{4})$/', '$1-$2-$3', $official->phone)
                                        : $official->phone;
                                @endphp
                                {{ $formattedPhone }}
                            </td>
                            <td>{{ $official->birthdate }}</td>
                            <td>{{ $official->achievements }}</td>
                            <td>{{ $official->email }}</td>
                            <td>{{ $official->position }}</td>
                            <td>{{ $official->term_start }}</td>
                            <td>{{ $official->term_end }}</td>
                            <td>
                                <!-- Edit Button (Modified to Open Modal) -->
                                <button class="btn btn-warning btn-sm editOfficialBtn"
                                    href="{{ route('admin.officials.edit', $official->id) }}"
                                    data-id="{{ $official->id }}"
                                    data-photo="{{ $official->photo }}"
                                    data-name="{{ $official->name }}"
                                    data-phone="{{ $official->phone }}"
                                    data-email="{{ $official->email }}"
                                    data-birthdate="{{ $official->birthdate }}"
                                    data-achievements="{{ $official->achievements }}"
                                    data-position="{{ $official->position }}"
                                    data-term_start="{{ $official->term_start }}"
                                    data-term_end="{{ $official->term_end }}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>

                                <!-- Delete Form -->
                                <form action="{{ route('admin.officials.destroy', $official->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger delete-btn m-1">
                                        <i class="fas fa-trash"></i>Delete
                                    </button>
                                </form>
                            </td>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Official Modal -->
    <div id="modal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Official</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="officialForm" action="{{ route('admin.officials.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3" style="min-height: 300px;">
                            <div class="col-md-6 d-flex flex-column align-items-center justify-content-center text-center">
                                <label class="form-label mb-2">Official Photo</label>
                            
                                <!-- Tooltip added here -->
                                <label for="photoInput" data-bs-toggle="tooltip" title="Click to upload photo">
                                    <img 
                                        src="https://cdn-icons-png.flaticon.com/512/847/847969.png" 
                                        id="photoPreview" 
                                        class="rounded-circle border border-primary" 
                                        style="width: 100px; height: 100px; object-fit: cover; cursor: pointer;" 
                                        alt="Preview"
                                    >
                                </label>
                            
                                <!-- Hidden file input -->
                                <input type="file" name="photo" id="photoInput" class="form-control d-none" accept="image/*">
                            </div>
                            

                            

                            
                            
                            
                            <div class="row">
                            <div class="col-md-6">
                                <label class="form-label" required>Full Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" required>Phone</label>
                                <input type="text" name="phone" class="form-control">
                            </div>
</div>


                            <div class="col-md-6">
                                <label class="form-label" required>Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Achievements (optional)</label>
                                <input type="text" name="achievements" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Birthdate</label>
                                <input type="date" name="birthdate" class="form-control" max="{{ now()->toDateString() }}">
                            </div>


                            {{-- maghahide kapag nakuha na yung maximum na value or na add na position. --}}
                            <div class="col-md-6">
                                <label class="form-label" required>Position</label>
                                <select name="position" class="form-select">
                                    @php
                                        $skKagawadCount = $officials->where('position', 'SK Kagawad')->count();
                                        $skChairpersonTaken = $officials->contains('position', 'SK Chairperson');
                                        $skSecretaryTaken = $officials->contains('position', 'SK Secretary');
                                        $skTreasurerTaken = $officials->contains('position', 'SK Treasurer');
                                    @endphp

                                    <option value="SK Chairperson"
                                        {{ isset($official) && $official->position == 'SK Chairperson' ? 'selected' : '' }}
                                        {{ (!isset($official) && $skChairpersonTaken) ? 'disabled' : '' }}>
                                        SK Chairperson
                                    </option>

                                    <option value="SK Kagawad"
                                        {{ isset($official) && $official->position == 'SK Kagawad' ? 'selected' : '' }}
                                        {{ (!isset($official) && $skKagawadCount >= 7) ? 'disabled' : '' }}>
                                        SK Kagawad
                                    </option>

                                    <option value="SK Secretary"
                                        {{ isset($official) && $official->position == 'SK Secretary' ? 'selected' : '' }}
                                        {{ (!isset($official) && $skSecretaryTaken) ? 'disabled' : '' }}>
                                        SK Secretary
                                    </option>

                                    <option value="SK Treasurer"
                                        {{ isset($official) && $official->position == 'SK Treasurer' ? 'selected' : '' }}
                                        {{ (!isset($official) && $skTreasurerTaken) ? 'disabled' : '' }}>
                                        SK Treasurer
                                    </option>
                                </select>
                            </div>






                            <div class="col-md-6">
                                <label class="form-label" required>Term Start</label>
                                <input type="date" name="term_start" class="form-control" id="term_start" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" required>Term End</label>
                                <input type="date" name="term_end" class="form-control" id="term_end" required>
                            </div>
                        </div>

                        <div class="modal-footer mt-4">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if ($errors->any() && !$errors->has('error'))
    <script>
        Swal.fire({
            title: 'Checking...',
            didOpen: () => {
                Swal.showLoading();
            },
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    
        setTimeout(() => {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#d33'
            });
        }, 1000);
    </script>
    @endif
    
    @if (session('success'))
    <script>
        Swal.fire({
            title: 'Processing...',
            didOpen: () => {
                Swal.showLoading();
            },
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    
        setTimeout(() => {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session("success") }}',
                confirmButtonColor: '#3085d6'
            });
        }, 1000); // Show success after 1 second
    </script>
    @endif
    


{{-- Validation for term_start --}}
@if ($errors->has('term_start'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Invalid Term Start',
        text: '{{ $errors->first("term_start") }}',
        confirmButtonColor: '#d33'
    });
</script>
@endif

{{-- Validation for term_end --}}
@if ($errors->has('term_end'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Invalid Term End',
        text: '{{ $errors->first("term_end") }}',
        confirmButtonColor: '#d33'
    });
</script>
@endif


    <script>
        document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function (e) {
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
    document.addEventListener("DOMContentLoaded", function () {
        let successMessage = document.getElementById("successMessage");
        let errorMessages = document.querySelectorAll(".errorMessage");

        function fadeEffect(element) {
            element.style.opacity = 0;
            element.style.transition = "opacity 0.5s ease-in-out";
            setTimeout(() => element.style.opacity = 1, 100);
            setTimeout(() => {
                element.style.opacity = 0;
                setTimeout(() => element.remove(), 500);
            }, 3000);
        }

        if (successMessage) {
            fadeEffect(successMessage);
        }

        errorMessages.forEach(error => {
            fadeEffect(error);
        });
    });




    document.addEventListener("DOMContentLoaded", function () {
        console.log("Script loaded!"); // Debugging: Check if script runs


        let modalElement = document.getElementById('modal');
        if (!modalElement) {
            console.error("Modal element not found!");
            return;
        }

        let modal = new bootstrap.Modal(modalElement);
        let modalTitle = document.querySelector(".modal-title");
        let form = document.getElementById('officialForm');
        let positionField = form.querySelector('select[name="position"]');

        let openModalButton = document.getElementById('openModal');

        // Open Add Modal
        if (openModalButton) {
    openModalButton.addEventListener('click', function () {
        console.log("Add Official button clicked");

        // Show loading SweetAlert
        Swal.fire({
            title: 'Loading',
            html: 'Please wait...',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Simulate loading delay before showing modal
        setTimeout(() => {
            Swal.close();

            form.reset(); 
            modalTitle.textContent = "Add Official";
            form.action = "{{ route('admin.officials.store') }}";


            let existingMethodInput = form.querySelector('input[name="_method"]');
            if (existingMethodInput) {
                existingMethodInput.remove();
            }

            if (positionField) {
                positionField.disabled = false;
            }

            modal.show();
        }, 2000); 
    });
} else {
    console.error("Add Official button not found!");
}



        // Use event delegation for Edit buttons
        document.addEventListener("click", function (event) {
    if (event.target.classList.contains("editOfficialBtn")) {
        let button = event.target;
        console.log("Edit button clicked! ID:", button.dataset.id);

        // Show loading SweetAlert
        Swal.fire({
            title: 'Loading Record',
            html: 'Please wait while we retrieve the information...',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Simulate loading delay before showing modal
        setTimeout(() => {
            Swal.close(); 

            modalTitle.textContent = "Edit Official";
            form.action = "/admin/officials/update/" + button.dataset.id;

            // Remove any existing _method input before adding a new one
            let existingMethodInput = form.querySelector('input[name="_method"]');
            if (existingMethodInput) {
                existingMethodInput.remove();
            }

            // Add PUT method for updating
            let methodInput = document.createElement("input");
            methodInput.type = "hidden";
            methodInput.name = "_method";
            methodInput.value = "PUT";
            form.appendChild(methodInput);

            // Populate form fields
            form.querySelector('input[name="name"]').value = button.dataset.name || '';
            form.querySelector('input[name="phone"]').value = button.dataset.phone || '';
            form.querySelector('input[name="email"]').value = button.dataset.email || '';
            form.querySelector('input[name="birthdate"]').value = button.dataset.birthdate || '';
            form.querySelector('input[name="achievements"]').value = button.dataset.achievements || '';
            form.querySelector('input[name="term_start"]').value = button.dataset.term_start || '';
            form.querySelector('input[name="term_end"]').value = button.dataset.term_end || '';

            positionField.value = button.dataset.position;
            positionField.disabled = true; 

                        // Update photo preview
                        let photoPreview = document.getElementById('photoPreview');
            if (button.dataset.photo) {
                photoPreview.src = "{{ asset('') }}" + button.dataset.photo; // Set the image source to the official's photo path
            } else {
                photoPreview.src = "https://cdn-icons-png.flaticon.com/512/847/847969.png"; 
            }

            
            modal.show();
        }, 2000); 
    }
});


        if (document.getElementById('openModal')) {
            document.getElementById('openModal').addEventListener('click', function () {
                form.reset();
                positionField.disabled = false; // Enable position for adding
            });
        }
    });

    // live preview
        document.getElementById('photoInput').addEventListener('change', function(e) {
            const [file] = e.target.files;
            if (file) {
                const preview = document.getElementById('photoPreview');
                preview.src = URL.createObjectURL(file);
            }
        });

            // Enable all tooltips on page
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });



    document.getElementById('term_start').addEventListener('change', function () {
        const startDate = new Date(this.value);
        if (!isNaN(startDate)) {
            const endInput = document.getElementById('term_end');
            const minDate = new Date(startDate);
            const maxDate = new Date(startDate);
            maxDate.setFullYear(maxDate.getFullYear() + 6);

            endInput.min = minDate.toISOString().split('T')[0];
            endInput.max = maxDate.toISOString().split('T')[0];
        }
    });
</script>

</x-app-layout>
