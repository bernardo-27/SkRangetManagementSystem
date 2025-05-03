<x-app-layout>
    <!-- QR Code Generator Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Announcements') }}
        </h2>
    </x-slot>

    <div id="mainContent" class="transition duration-300">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white mt-6 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Recent Announcements</h3>

                    <div class="mb-4">
                        <input
                            type="text"
                            id="searchInput"
                            placeholder="Search announcements by title..."
                            class="w-full sm:w-64 p-2 border rounded"
                        >
                    </div>

                    @foreach($announcements as $announcement)
                        <div class="announcement-item p-4 border-b">
                            <div class="flex justify-between items-center">
                                <h4 class="announcement-title font-bold">{{ $announcement->title }}</h4>
                                <button onclick="showQRCodeModal('{{ route('users.announcements.show', ['id' => $announcement->id]) }}')"
                                    class="bg-primary text-black px-4 py-2 rounded flex items-center gap-2 hover:bg-blue-700 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18m-9 5h9" />
                                    </svg>
                                    QR Code
                                </button>
                            </div>
                            <p class="text-gray-700">{{ $announcement->content }}</p>
                            <small class="text-gray-500">
                                {{ $announcement->created_at ? $announcement->created_at->format('F d, Y h:i A') : 'N/A' }}
                            </small>
                        </div>
                    @endforeach

                    @if($announcements->isEmpty())
                        <p class="text-gray-500">No announcements yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- QR Code Modal -->
    <div id="qrCodeModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-xl relative shadow-lg">
            <!-- Close Button -->
            <button onclick="closeQRCodeModal()" class="absolute top-2 right-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 hover:text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <h3 class="text-xl font-bold mb-2 text-center text-primary">QR Code</h3>
            <p class="text-sm text-gray-600 mb-4 text-center">Scan to view this announcement</p>

            <div id="qrCodeContainer" class="flex justify-center items-center p-4 border rounded-lg">
                <!-- QR Code will appear here -->
            </div>

            <p class="text-xs text-primary mt-4 text-center">Shareable Link:</p>
            <div class="flex items-center gap-2 mt-2">
                <input id="shareLink" type="text" readonly class="border rounded w-full px-2 py-1 text-sm">
                <button onclick="copyLink()" class="bg-primary text-white px-2 py-1 rounded">Copy</button>
            </div>

            <p class="text-xs text-gray-500 mt-4 text-center">Share this QR or link to let others access this announcement.</p>
        </div>
    </div>

    <!-- Add SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Show QR Code Modal
            function showQRCodeModal(link) {
                if (!link) {
                    Swal.fire('Oops!', 'Invalid announcement link', 'error');
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

            // Copy Link Functionality
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
                            width: '300px',
                            padding: '1rem',
                            iconColor: '#16a34a',
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

            // Close QR Code Modal
            function closeQRCodeModal() {
                document.getElementById('qrCodeModal').classList.add('hidden');
                document.getElementById('mainContent').classList.remove('blur-sm');
            }

            // Click outside modal to close it
            document.getElementById('qrCodeModal').addEventListener('click', function (event) {
                if (event.target === this) {
                    closeQRCodeModal();
                }
            });

            // Search Filter by Title
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
