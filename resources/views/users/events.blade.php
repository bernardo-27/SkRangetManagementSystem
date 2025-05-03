<x-app-layout>

    <!-- QR Code Generator Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <x-slot name="header">
        <h2 class="text-center text-primary fw-bold">Upcoming Events</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg fs-bold mb-4">Upcoming Events</h3>

                    <!-- Search Input Field -->
                    <input type="text" id="eventSearch" onkeyup="searchEvents()" placeholder="Search by event title..." class="w-full px-4 py-2 mb-4 border rounded-md">

                    <div id="eventsContainer">
                        @foreach($events as $event)
                            <div class="p-4 border-b event-item">
                                <h4 class="fw-bold fst-italic event-title">{{ $event->title }}</h4>
                                <p class="text-gray-700">{{ $event->description }}</p>
                                <a href="{{ route('users.events.show', ['id' => $event->id]) }}"
                                    class="text-blue-500 underline"
                                    target="_blank">View Event</a>
                                @if($event->image)
                                    @php
                                        $images = json_decode($event->image);
                                    @endphp
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($images as $img)
                                            <img src="{{ asset('storage/' . $img) }}" alt="Event Image" class="w-25 h-25 object-cover rounded">
                                        @endforeach
                                    </div>
                                @else
                                    <p>No images available.</p>
                                @endif

                                <small class="text-gray-500">
                                    {{ $event->created_at->format('F d, Y h:i A') }}
                                </small>

                                <button onclick="showQRCodeModal('{{ route('users.events.show', ['id' => $event->id]) }}')"
                                    class="bg-primary top-0 text-white px-4 py-2 rounded flex items-center gap-2 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18m-9 5h9" />
                                    </svg>
                                    QR Code
                                </button>
                            </div>
                        @endforeach
                    </div>

                    @if($events->isEmpty())
                        <p class="text-gray-500">No events yet.</p>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- QR Code Modal -->
    <div id="qrCodeModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-xl relative shadow-lg">
            <button onclick="closeQRCodeModal()" class="absolute top-2 right-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 hover:text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <h3 class="text-xl font-bold mb-2 text-center text-primary">Event QR Code</h3>
            <p class="text-sm text-gray-600 mb-4 text-center">Scan to view event details</p>

            <!-- QR Code -->
            <div id="qrCodeContainer" class="flex justify-center items-center p-4 border rounded-lg">
                <!-- QR Code will be placed here -->
            </div>

            <p class="text-xs text-primary mt-4 text-center">Share this link:</p>
            <div class="flex items-center justify-between gap-2">
                <input id="shareLink" type="text" readonly class="border w-full rounded">
            </div>
            <div style="display: flex; justify-content: center;">
                <button onclick="copyLink()" class="bg-primary text-white px-2 py-1 mt-2 rounded">Copy Link</button>
            </div>

            <p class="text-xs text-gray mt-4 text-center">Scan this QR code to access event details instantly.</p>
        </div>
    </div>

    <!-- Add SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function showQRCodeModal(link) {
            if (!link) {
                Swal.fire('Oops!', 'Invalid event link', 'error');
                return;
            }

            const qrCodeContainer = document.getElementById('qrCodeContainer');
            const shareLinkInput = document.getElementById('shareLink');

            qrCodeContainer.innerHTML = "";

            new QRCode(qrCodeContainer, {
                text: link,
                width: 200,
                height: 200
            });

            shareLinkInput.value = link;

            document.getElementById('qrCodeModal').classList.remove('hidden');
            document.getElementById('mainContent').classList.add('blur-sm');
        }

        function copyLink() {
            const shareLinkInput = document.getElementById('shareLink');
            navigator.clipboard.writeText(shareLinkInput.value)
                .then(() => {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Link copied!',
                        showConfirmButton: false,
                        timer: 1200,
                        width: '300px', // smaller width
                        padding: '1rem',
                        iconColor: '#16a34a', // Tailwind green-600
                        customClass: {
                            popup: 'text-sm rounded-md'
                        }
                    });
                })
                .catch(() => {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Failed to copy!',
                        text: 'Please try again.',
                        showConfirmButton: false,
                        timer: 1500,
                        width: '300px',
                        padding: '1rem',
                        customClass: {
                            popup: 'text-sm rounded-md'
                        }
                    });
                });
        }

        function closeQRCodeModal() {
            document.getElementById('qrCodeModal').classList.add('hidden');
            document.getElementById('mainContent').classList.remove('blur-sm');
        }

        document.getElementById('qrCodeModal').addEventListener('click', function (event) {
            if (event.target === this) {
                closeQRCodeModal();
            }
        });

        function searchEvents() {
            const searchInput = document.getElementById('eventSearch').value.toLowerCase();
            const events = document.querySelectorAll('.event-item');
            events.forEach(event => {
                const title = event.querySelector('.event-title').textContent.toLowerCase();
                if (title.includes(searchInput)) {
                    event.style.display = '';
                } else {
                    event.style.display = 'none';
                }
            });
        }
    </script>

</x-app-layout>
