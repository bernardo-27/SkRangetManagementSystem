<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Users List') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td id="status-{{ $user->id }}">
                                        <span class="badge
                                            @if($user->status == 'pending') bg-warning
                                            @elseif($user->status == 'accepted') bg-success
                                            @else bg-danger @endif">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                    <td id="action-{{ $user->id }}">
                                        <!-- Only show Accept/Reject buttons if not already in that state -->
                                        @if($user->status != 'accepted')
                                            <button type="button" class="btn btn-success btn-sm accept-btn" data-id="{{ $user->id }}">Accept</button>
                                        @endif
                                        
                                        @if($user->status != 'rejected')
                                            <button type="button" class="btn btn-danger btn-sm reject-btn" data-id="{{ $user->id }}">Reject</button>
                                        @endif
                                        
                                        <!-- Remove Button - Only show for rejected users -->
                                        @if($user->status == 'rejected')
                                            <form id="remove-form-{{ $user->id }}" action="/admin/users/{{ $user->id }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-dark btn-sm remove-btn" data-id="{{ $user->id }}">
                                                    Remove
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Include SweetAlert2 from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const updateStatus = (userId, status) => {
                // Show loading indicator
                const statusCell = document.getElementById(`status-${userId}`);
                const originalStatus = statusCell.innerHTML;
                statusCell.innerHTML = '<span class="spinner-border spinner-border-sm text-secondary" role="status"></span> Updating...';
                
                fetch(`/admin/users/${userId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Update the status badge with appropriate color
                        let badgeClass = status === 'accepted' ? 'bg-success' : 'bg-danger';
                        statusCell.innerHTML = `<span class="badge ${badgeClass}">${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
                        
                        // Update the action cell based on the new status
                        const actionCell = document.getElementById(`action-${userId}`);
                        if (status === 'accepted') {
                            // Remove both buttons and show success message temporarily
                            actionCell.innerHTML = `<span class="text-success">✓ User Accepted</span>`;
                            setTimeout(() => {
                                // After timeout, keep only reject option
                                actionCell.innerHTML = `<button type="button" class="btn btn-danger btn-sm reject-btn" data-id="${userId}">Reject</button>`;
                                // Re-attach event listener
                                const newRejectBtn = actionCell.querySelector('.reject-btn');
                                if (newRejectBtn) {
                                    newRejectBtn.addEventListener('click', function() {
                                        updateStatus(userId, 'rejected');
                                    });
                                }
                            }, 1500);
                        } else if (status === 'rejected') {
                            // Show reject confirmation and add remove button
                            actionCell.innerHTML = `
                                <span class="text-danger">✓ User Rejected</span>
                                <form id="remove-form-${userId}" action="/admin/users/${userId}" method="POST" class="mt-2 d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-dark btn-sm remove-btn" data-id="${userId}">
                                        Remove
                                    </button>
                                </form>
                            `;
                            setTimeout(() => {
                                // After timeout, show accept button and keep remove button
                                actionCell.innerHTML = `
                                    <button type="button" class="btn btn-success btn-sm accept-btn" data-id="${userId}">Accept</button>
                                    <form id="remove-form-${userId}" action="/admin/users/${userId}" method="POST" class="d-inline-block ml-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-dark btn-sm remove-btn" data-id="${userId}">
                                            Remove
                                        </button>
                                    </form>
                                `;
                                // Re-attach event listeners
                                const newAcceptBtn = actionCell.querySelector('.accept-btn');
                                if (newAcceptBtn) {
                                    newAcceptBtn.addEventListener('click', function() {
                                        updateStatus(userId, 'accepted');
                                    });
                                }
                                
                                const newRemoveBtn = actionCell.querySelector('.remove-btn');
                                if (newRemoveBtn) {
                                    newRemoveBtn.addEventListener('click', function() {
                                        confirmRemove(userId);
                                    });
                                }
                            }, 1500);
                        }

                        
                        // Store status in localStorage to persist across page reloads
                        localStorage.setItem(`user-${userId}-status`, status);
                    } else {
                        // If update failed, restore original status
                        statusCell.innerHTML = originalStatus;
                        Swal.fire({
                            title: 'Error!',
                            text: data.message || 'Failed to update status.',
                            icon: 'error'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // If there was an error, restore original status
                    statusCell.innerHTML = originalStatus;
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while updating the status.',
                        icon: 'error'
                    });
                });
            };
            
            // Function to handle remove confirmation with SweetAlert
            const confirmRemove = (userId) => {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will permanently delete this user account. This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If confirmed, submit the form
                        const form = document.getElementById(`remove-form-${userId}`);
                        if (form) {
                            form.submit();
                        }
                    }
                });
            };

            // Attach event listeners to all buttons
            const attachEventListeners = () => {
                // Select all accept buttons
                const acceptButtons = document.querySelectorAll('.accept-btn');
                acceptButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const userId = this.getAttribute('data-id');
                        updateStatus(userId, 'accepted');
                    });
                });

                // Select all reject buttons
                const rejectButtons = document.querySelectorAll('.reject-btn');
                rejectButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const userId = this.getAttribute('data-id');
                        updateStatus(userId, 'rejected');
                    });
                });
                
                // Select all remove buttons
                const removeButtons = document.querySelectorAll('.remove-btn');
                removeButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const userId = this.getAttribute('data-id');
                        confirmRemove(userId);
                    });
                });
            };

            // Check localStorage for stored statuses on page load
            const checkStoredStatuses = () => {
                const userRows = document.querySelectorAll('tbody tr');
                userRows.forEach(row => {
                    const actionBtn = row.querySelector('.accept-btn, .reject-btn');
                    if (actionBtn) {
                        const userId = actionBtn.getAttribute('data-id');
                        const storedStatus = localStorage.getItem(`user-${userId}-status`);
                        if (storedStatus) {
                            // If we have a stored status, update the UI accordingly
                            const statusElement = document.getElementById(`status-${userId}`);
                            const currentStatus = statusElement.textContent.trim().toLowerCase();
                            if (currentStatus !== storedStatus) {
                                // Only update if the current display doesn't match stored status
                                updateStatus(userId, storedStatus);
                            }
                        }
                    }
                });
            };

            // Initialize
            attachEventListeners();
            checkStoredStatuses();
        });
    </script>
</x-app-layout>