
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">


    <div class="container mt-4">
        <h2 class="fw-semibold fs-4 text-dark">
                    Event Details
        </h2>
        <small class="d-block text-muted mt-3 fw-bold fst-italic">Posted on {{ $event->created_at->format('F d, Y h:i A') }}</small>

    </div>


    <div class="py-5">
        <div class="container">
            <div class="card shadow-sm rounded">
                <div class="card-body">

                    <h3 class="card-title fs-5 fw-bold">{{ $event->title }}</h3>
                    <p class="card-text text-muted">{{ $event->description }}</p>

                    @if($event->image)
                        @php $images = json_decode($event->image); @endphp
                        <div class="d-flex flex-wrap gap-3 mt-3 justify-content-center">
                            @foreach($images as $img)
                                <img src="{{ asset('storage/' . $img) }}" alt="Event Image" class="rounded cursor-pointer"
                                    style="width: 150px; height: 100px; object-fit: cover; "
                                    onclick="openModal('{{ asset('storage/' . $img) }}')">
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No images available.</p>
                    @endif

                    <small class="d-block text-muted mt-3 text-center">Posted on {{ $event->created_at->format('F d, Y h:i A') }}</small>

                    <!-- Back Button -->
                    <div class="mt-4">
                        {{-- <a href="{{ route('users.events.show') }}" class="btn btn-primary">Back to Events</a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ModaImage -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="eventsImage" class="img-fluid" alt="Zoomed Image">
                </div>

            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        // open the modal image
        function openModal(imageSrc) {
            const modal = new bootstrap.Modal(document.getElementById('imageModal'));
            document.getElementById('eventsImage').src = imageSrc;
            modal.show();
        }
    </script>

