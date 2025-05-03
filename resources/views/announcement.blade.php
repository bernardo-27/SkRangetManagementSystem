<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Announcements') }}
        </h2>
    </x-slot>

    <!-- Main Content Wrapper -->
    <div id="mainContent" class="transition duration-300">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white mt-6 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Recent Announcements</h3>

                    @foreach($announcements as $announcement)
                        <div class="p-4 border-b">
                            <div class="flex justify-between items-center">
                                <h4 class="font-bold">{{ $announcement->title }}</h4>
                                <button onclick="showQRCodeModal({{ $announcement->id }})"
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

                            <!-- Hidden QR Code Data -->
                            <div id="qrCodeData{{ $announcement->id }}" class="hidden">
                                {!! $announcement->qr_code !!}
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

    <!-- QR Code Modal -->
    <div id="qrCodeModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-sm w-sm relative shadow-lg">
            <button onclick="closeQRCodeModal()" class="absolute top-2 right-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 hover:text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="text-xl font-bold mb-2 text-center text-blue-600">Rise and Lead</h3>
            <p class="text-sm text-gray-600 mb-4 text-center">QR Code Integration of SK Information in Ranget, Tagudin, Ilocos Sur</p>
            <div id="qrCodeContainer" class="flex justify-center items-center p-4 border border-gray-300 rounded-lg">
                <!-- QR Code will be placed here -->
            </div>
            <p class="text-xs text-gray-500 mt-4 text-center">Scan this QR code to access essential SK Announcement instantly.</p>
        </div>
    </div>


    <script>
        function showQRCodeModal(announcementId) {
            // Get QR code data
            const qrCodeData = document.getElementById('qrCodeData' + announcementId).innerHTML;

            // Place QR code in the modal
            document.getElementById('qrCodeContainer').innerHTML = qrCodeData;

            // Show the modal
            document.getElementById('qrCodeModal').classList.remove('hidden');

            // Apply blur to main content
            document.getElementById('mainContent').classList.add('blur-sm');
        }

        function closeQRCodeModal() {
            // Hide the modal
            document.getElementById('qrCodeModal').classList.add('hidden');

            // Remove blur from main content
            document.getElementById('mainContent').classList.remove('blur-sm');
        }

        // Close modal when clicking outside of it
        document.getElementById('qrCodeModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeQRCodeModal();
            }
        });
    </script>
</x-app-layout>
