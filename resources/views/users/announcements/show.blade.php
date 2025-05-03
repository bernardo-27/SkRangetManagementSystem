{{-- resources/views/users/announcements/show.blade.php --}}

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<div class="container mt-4">
    <h2 class="fw-semibold fs-4 text-primary text-center">Announcement Details</h2>
    <small class="d-block text-muted mt-3 fw-bold fst-italic text-center">
        Posted on {{ $announcement->created_at->format('F d, Y h:i A') }}
    </small>
</div>

<div class="py-5">
    <div class="container text-center">
        <div class="card shadow-sm rounded">
            <div class="card-body">

                <h3 class="card-title fs-5 fw-bold">{{ $announcement->title }}</h3>
                <p class="card-text text-muted">{{ $announcement->content }}</p>

                {{-- Optional Image Display (if needed) --}}
                @if($announcement->image)
                    @php $images = json_decode($announcement->image); @endphp
                    <div class="d-flex flex-wrap gap-3 mt-3 justify-content-center">
                        @foreach($images as $img)
                            <img src="{{ asset('storage/' . $img) }}" alt="Announcement Image" class="rounded cursor-pointer"
                                 style="width: 150px; height: 100px; object-fit: cover;"
                                 onclick="openImageModal('{{ asset('storage/' . $img) }}')">
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body text-center">
                <p class="text-muted">Scan to view this announcement</p>
                <div id="qrCodeContainer" class="d-flex justify-content-center mb-3"></div>

                <p class="text-muted small mb-1">Shareable Link:</p>
                <div class="input-group">
                    <input id="shareLink" type="text" class="form-control" readonly>
                    <button class="btn btn-outline-primary" onclick="copyLink()">Copy</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Optional Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalImage" class="img-fluid" alt="Zoomed Image">
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function showQRCodeModal(url) {
        const qrContainer = document.getElementById('qrCodeContainer');
        const shareInput = document.getElementById('shareLink');
        qrContainer.innerHTML = "";

        new QRCode(qrContainer, {
            text: url,
            width: 200,
            height: 200
        });

        shareInput.value = url;

        const modal = new bootstrap.Modal(document.getElementById('qrCodeModal'));
        modal.show();
    }

    function copyLink() {
        const shareInput = document.getElementById('shareLink');
        navigator.clipboard.writeText(shareInput.value)
            .then(() => alert('✅ Link copied to clipboard!'))
            .catch(() => alert('❌ Failed to copy link'));
    }

    function openImageModal(imageSrc) {
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        document.getElementById('modalImage').src = imageSrc;
        modal.show();
    }
</script>
