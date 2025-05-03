<x-app-layout>

    <!-- QR Code Generator Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>


        <x-slot name="header">
            <h2 class="text-center text-primary fw-bold">Officials List</h2>
        </x-slot>

        <div class="container">
            <ul class="list-group mt-4 ">
                @foreach($officials as $official)
                    <li class="list-group-item d-flex  justify-content-between align-items-center">


                        <!-- Name with Tooltip -->
                        <a href="#"
                        data-bs-toggle="modal"
                        data-bs-target="#officialModal{{ $official->id }}"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Click to view details about {{ $official->position }}"
                        class="d-flex align-items-center gap-2">

                        <!-- Official's Photo (if available) -->
                        @if($official->photo)
                            <img src="{{ asset($official->photo) }}" alt="{{ $official->name }}" class="img-fluid rounded " width="100">
                            <caption>{{ $official->name }}</caption>
                        @endif

                    </a>

                        <!-- QR Code Button -->
                        <button onclick="showQRCodeModal({{ $official->id }})"
                            class="bg-primary text-white px-4 py-2 rounded flex items-center gap-2  transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18m-9 5h9" />
                            </svg>
                            QR Code
                        </button>


                    </li>

                    <!-- Hidden QR Code Data -->
                    {{-- <div id="qrCodeData{{ $official->id }}" class="hidden">
                        {!! QrCode::size(200)->generate(route('users.sk-official.show', $official->id)) !!}
                    </div> --}}

                         <!-- Information Modal -->
                         <div class="modal fade" id="officialModal{{ $official->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $official->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel{{ $official->id }}">{{ $official->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">

                                        <!-- Photo -->
                                        @if($official->photo)
                                            <div class="d-flex justify-content-center">
                                                <img src="{{ asset($official->photo) }}" alt="{{ $official->name }}" class="img-fluid rounded" width="150">
                                            </div>
                                        @endif

                                        <p class="mt-3"><strong>Position:</strong> {{ $official->position }}</p>
                                        <p><strong>Term Start:</strong> {{ \Carbon\Carbon::parse($official->term_start)->format('F d, Y') }}</p>
                                        <p><strong>Term End:</strong> {{ \Carbon\Carbon::parse($official->term_end)->format('F d, Y') }}</p>

                                        @if($official->email)
                                            <p><strong>Email:</strong> <a href="mailto:{{ $official->email }}">{{ $official->email }}</a></p>
                                        @endif
                                        @if($official->phone && preg_match('/^\d{11}$/', $official->phone))
                                            @php
                                                $formattedPhone = preg_replace("/^(\d{4})(\d{3})(\d{4})$/", '$1-$2-$3', $official->phone);
                                            @endphp
                                            <p><strong>Phone:</strong> <a href="tel:{{ $official->phone }}">{{ $formattedPhone }}</a></p>
                                        @endif


                                        @if($official->birthdate)
                                            <p><strong>Birthdate:</strong> {{ \Carbon\Carbon::parse($official->birthdate)->format('F d, Y') }}</p>
                                        @endif
                                        @if($official->achievements)
                                            <p><strong>Achievements:</strong> {{ $official->achievements }}</p>
                                        @endif

                                        <!-- Centered QR Code -->
                                        {{-- <div class="d-grid justify-content-center">
                                        <p class="mt-4  mb-2 text-center"><strong>QR Code:</strong></p>
                                            {!! QrCode::size(150)->generate(route('users.sk-official.show', $official->id)) !!}
                                        </div> --}}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                @endforeach
            </ul>
        </div>

    </ul>
    </div>







        <!-- QR Code Modal -->
        <div id="qrCodeModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-sm w-sm relative shadow-lg">
                <button onclick="closeQRCodeModal()" class="absolute top-2 right-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 hover:text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <h3 class="text-xl font-bold mb-2 text-center text-blue-600">SK Official QR Code</h3>
                <p class="text-sm text-gray-600 mb-4 text-center">Scan to view official details</p>
                <div id="qrCodeContainer" class="flex justify-center items-center p-4 border border-gray-300 rounded-lg">
                    <!-- QR Code will be placed here -->
                </div>

                            <!-- Shareable Link -->
            <p class="text-xs text-primary mt-4 text-center">Share this link:</p>
            <div class="flex items-center justify-between gap-2">
                <input id="shareLink" type="text" readonly class="border  w-full rounded">
            </div>
            <div style="display: flex; justify-content: center;">
                <button onclick="copyLink()" class="bg-primary text-white px-2 py-1 mt-2 rounded">Copy Link</button>
            </div>

                <p class="text-xs text-gray mt-4 text-center">Scan this QR code to access SK Official's information instantly.</p>
            </div>
        </div>


    <!-- Add SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>

        document.addEventListener("DOMContentLoaded", function () {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });

        // Open QR Code Modal and Generate QR Code + Link
    // Open QR Code Modal and Generate QR Code + Link
    function showQRCodeModal(officialId) {
        // Validate officialId
        if (!officialId) {
            console.error('❌ Error: Invalid officialId');
            alert("Error: Invalid official ID.");
            return;
        }

        // Generate shareable link
        const officialURL = `${window.location.origin}/users/sk-officials/${officialId}`;
        console.log('✅ Generated Link:', officialURL);

        // Get elements
        const qrCodeContainer = document.getElementById('qrCodeContainer');
        const shareLinkInput = document.getElementById('shareLink');

        // Clear previous QR Code
        qrCodeContainer.innerHTML = "";

        // Generate and display QR Code
        new QRCode(qrCodeContainer, {
            text: officialURL,
            width: 200,
            height: 200
        });

        // Display link for copying
        shareLinkInput.value = officialURL;

        // Show the modal
        document.getElementById('qrCodeModal').classList.remove('hidden');
    }

    // Copy link to clipboard
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

    // Close QR Code Modal
    function closeQRCodeModal() {
        document.getElementById('qrCodeModal').classList.add('hidden');
    }

        </script>


    </x-app-layout>
