<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Events') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Create a New Event</h3>

                    <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="title" required class="w-full p-2 border rounded">
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" required class="w-full p-2 border rounded"></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="images" class="block text-sm font-medium text-gray-700">Upload Images (Multiple)</label>
                            <input type="file" name="image[]" id="images" class="w-full p-2 border rounded" multiple>
                        </div>

                        <div id="preview-container" class="flex flex-wrap gap-2"></div>

                        <button type="submit" class="bg-primary text-white px-4 py-2 font-bold rounded mt-4">Create Event</button>
                    </form>


                    {{-- Uploaded Event --}}
                    <div class="bg-white mt-5 shadow-sm sm:rounded-lg ">
                        <h3 class="text-lg fw-bold mb-4">Upcoming Events</h3>
                        <div class="mb-4">
                            <input type="text" id="event-search" placeholder="Search by title..."
                                class="p-2 border rounded w-full sm:w-64" />
                        </div>

                        @forelse($events as $event)
                            <div class="p-4 border-b event-item" data-title="{{ strtolower($event->title) }}">
                                <h4 class="font-bold">{{ $event->title }}</h4>
                                <p class="text-gray-700">{{ $event->description }}</p>

                                @php
                                    $images = $event->image ? json_decode($event->image) : [];
                                @endphp

                                @if (!empty($images))
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        @foreach($images as $img)
                                            <img src="{{ asset('storage/' . $img) }}" alt="Event Image" class="w-32 h-20 object-cover rounded">
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500">No images available.</p>
                                @endif

                                <small class="text-gray-500 real-time-timestamp" data-timestamp="{{ $event->created_at?->toISOString() }}">
                                    {{ $event->created_at ? $event->created_at->format('F d, Y h:i A') : 'N/A' }}
                                </small>

                                <div class="mt-2 flex gap-2">
                                    <a href="{{ route('admin.events.edit', $event->id) }}" class="bg-primary text-white px-3 py-2 rounded">Edit</a>

                                    <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return false;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="bg-red-600 text-white px-3 py-2 rounded delete-btn">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">No events yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SweetAlert Success --}}
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

    {{-- SweetAlert Delete Confirmation --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
        });
    </script>

    {{-- Real-time Timestamp --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
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
        });
    </script>

    {{-- Image Preview and Zoom Modal --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let previewContainer = document.getElementById('preview-container');
            let imageInput = document.getElementById('images');

            let modal = document.createElement('div');
            modal.id = 'image-modal';
            modal.className = 'fixed inset-0 flex items-center justify-center bg-black bg-opacity-75 hidden z-50';
            modal.innerHTML = `
                <div class="relative bg-white p-4 rounded-lg">
                    <button id="close-modal" class="absolute top-2 right-2 text-black text-2xl font-bold">&times;</button>
                    <img id="modal-image" class="w-auto max-h-[90vh] rounded-lg m-4" />
                    <button id="remove-image" class="mt-4 bg-red-600 text-white px-4 py-2 rounded">Remove</button>
                </div>`;
            document.body.appendChild(modal);

            document.getElementById('close-modal').addEventListener('click', () => {
                modal.classList.add('hidden');
            });

            imageInput.addEventListener('change', function (event) {
                previewContainer.innerHTML = '';
                Array.from(event.target.files).forEach((file, index) => {
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        let imgContainer = document.createElement('div');
                        imgContainer.classList.add('relative');

                        let img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'object-cover rounded-lg border cursor-pointer';
                        img.style.width = "150px";
                        img.style.height = "100px";
                        img.dataset.index = index;

                        img.addEventListener('click', function () {
                            document.getElementById('modal-image').src = this.src;
                            document.getElementById('remove-image').dataset.index = this.dataset.index;
                            modal.classList.remove('hidden');
                        });

                        imgContainer.appendChild(img);
                        previewContainer.appendChild(imgContainer);
                    };
                    reader.readAsDataURL(file);
                });
            });

            document.getElementById('remove-image').addEventListener('click', function () {
                let index = this.dataset.index;
                let imagesArray = Array.from(imageInput.files);

                imagesArray.splice(index, 1);

                let dataTransfer = new DataTransfer();
                imagesArray.forEach(file => dataTransfer.items.add(file));
                imageInput.files = dataTransfer.files;

                previewContainer.innerHTML = '';
                imageInput.dispatchEvent(new Event('change'));
                modal.classList.add('hidden');
            });
        });
    </script>

    {{-- Search Filtering --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('event-search');
            const eventItems = document.querySelectorAll('.event-item');

            searchInput.addEventListener('keyup', function () {
                const searchTerm = this.value.toLowerCase();
                eventItems.forEach(item => {
                    const title = item.getAttribute('data-title');
                    item.style.display = title.includes(searchTerm) ? 'block' : 'none';
                });
            });
        });
    </script>
</x-app-layout>
