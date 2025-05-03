<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Announcements') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Post a New Announcement</h3>

                    <!-- Announcement Form -->
                    <form method="POST" action="{{ route('admin.announcement.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="title" required class="w-full p-2 border rounded">
                        </div>
                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                            <textarea name="content" id="content" required class="w-full p-2 border rounded"></textarea>
                        </div>
                        <button type="submit" class="bg-primary text-white px-4 py-2 fw-bold rounded">Post</button>
                    </form>

                    <!-- Display Announcements -->
                    <div class="bg-white mt-6 shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-4">Recent Announcements</h3>

                        <!-- Search Input -->
                        <div class="mb-4">
                            <input 
                                type="text" 
                                id="searchInput" 
                                placeholder="Search announcements by title..." 
                                class="w-full sm:w-64 p-2 border rounded"
                            >
                        </div>

                        @foreach($announcements as $announcement)
                        <div class="p-4 border-b announcement-item">
                            <h4 class="font-bold announcement-title">{{ $announcement->title }}</h4>
                            <p class="text-gray-700">{{ $announcement->content }}</p>
                            <small class="text-gray-500 real-time-timestamp" data-timestamp="{{ $announcement->created_at?->toISOString() }}">
                                {{ $announcement->created_at ? $announcement->created_at->format('F d, Y h:i A') : 'N/A' }}
                            </small>

                            <div class="mt-2">
                                <!-- Edit Button -->
                                <a href="{{ route('admin.announcement.edit', $announcement->id) }}"
                                    class="bg-primary text-white px-3 py-2 rounded">
                                    Edit
                                </a>

                                <!-- Delete Button -->
                                <form action="{{ route('admin.announcement.destroy', $announcement->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger delete-btn">Delete</button>
                                </form>
                            </div>
                        </div>
                        @endforeach

                        @if($announcements->isEmpty())
                            <p class="text-gray-500">No announcements yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        });
    </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Delete confirmation
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function () {
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
                            form.submit();
                        }
                    });
                });
            });

            // Timestamp auto-update
            function updateTimestamps() {
                document.querySelectorAll('.real-time-timestamp').forEach(el => {
                    let timestamp = el.getAttribute('data-timestamp');
                    if (timestamp) {
                        let date = new Date(timestamp);
                        if (!isNaN(date)) {
                            let formattedDate = date.toLocaleString('en-US', {
                                month: 'long',
                                day: '2-digit',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit',
                                second: '2-digit',
                                hour12: true
                            });
                            el.textContent = formattedDate;
                        }
                    }
                });
            }

            updateTimestamps();
            setInterval(updateTimestamps, 1000);

            // Search filter by title
            const searchInput = document.getElementById('searchInput');
            const announcements = document.querySelectorAll('.announcement-item');

            searchInput.addEventListener('input', function () {
                const searchTerm = this.value.toLowerCase();

                announcements.forEach(item => {
                    const title = item.querySelector('.announcement-title').textContent.toLowerCase();
                    if (title.includes(searchTerm)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    </script>
</x-app-layout>
