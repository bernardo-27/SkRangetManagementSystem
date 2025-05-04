<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <style>
        .alert {
            opacity: 1;
            transition: opacity 1s ease-in-out;
        }

        .alert.hide {
            opacity: 0;
        }

        .casual-btn {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .casual-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .carousel-container {
    background-color: #ffffff;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    padding: 1.5rem;
    margin-bottom: 2rem;
    overflow: hidden;
}

.carousel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.25rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #f0f0f0;
}

.carousel-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #333;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.carousel-title i {
    color: #0055A5;
}

.carousel-controls {
    display: flex;
    gap: 0.5rem;
}

.carousel-control-button {
    background: #f5f5f5;
    border: none;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.carousel-control-button:hover {
    background: #e0e0e0;
}

#imageCarousel {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
}

.carousel-inner {
    border-radius: 12px;
}

.carousel-item {
    height: 400px;
    position: relative;
}

.carousel-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 12px;
    transition: transform 0.5s ease;
}

.carousel-caption {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(0deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 70%, rgba(0,0,0,0) 100%);
    padding: 1.5rem;
    border-radius: 0 0 12px 12px;
    text-align: left;
    opacity: 1;
    transition: opacity 0.3s ease;
}

.carousel-item:hover .carousel-caption {
    opacity: 1;
}

.carousel-caption h5 {
    font-weight: 600;
    color: white;
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

.carousel-caption p {
    color: rgba(255, 255, 255, 0.85);
    margin-bottom: 0;
    font-size: 0.9rem;
}

.carousel-indicators {
    bottom: -40px;
}

.carousel-indicators [data-bs-target] {
    width: 10px;
    height: 10px;
    background-color: rgba(0, 85, 165, 0.3);
    border-radius: 50%;
    border: none;
    margin: 0 5px;
    opacity: 0.7;
    transition: all 0.3s ease;
}

.carousel-indicators .active {
    background-color: #0055A5;
    transform: scale(1.2);
    opacity: 1;
}

.carousel-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    background-color: rgba(255, 255, 255, 0.7);
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #333;
    font-size: 1.2em;
    cursor: pointer;
    opacity: 0;
    transition: all 0.3s ease;
    z-index: 10;
}

.carousel-container:hover .carousel-nav {
    opacity: 0.8;
}

.carousel-nav:hover {
    background-color: rgba(255, 255, 255, 0.9);
    opacity: 1 !important;
}

.carousel-nav.prev {
    left: 15px;
}

.carousel-nav.next {
    right: 15px;
}

.carousel-thumbnails {
    display: flex;
    gap: 10px;
    margin-top: 30px;
    overflow-x: auto;
    padding: 10px 0;
    scrollbar-width: thin;
}

.carousel-thumbnail {
    width: 80px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    opacity: 0.6;
    transition: all 0.2s ease;
    flex-shrink: 0;
    border: 2px solid transparent;
}

.carousel-thumbnail.active {
    opacity: 1;
    border-color: #0055A5;
}

.carousel-thumbnail:hover {
    opacity: 0.9;
}

.carousel-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .carousel-item {
        height: 250px;
    }

    .carousel-caption h5 {
        font-size: 0.95rem;
    }

    .carousel-caption p {
        font-size: 0.8rem;
    }

    .carousel-nav {
        width: 35px;
        height: 35px;
    }

    .carousel-thumbnail {
        width: 60px;
        height: 45px;
    }
}

@media (max-width: 576px) {
    .carousel-container {
        padding: 1rem;
    }

    .carousel-title {
        font-size: 1.1rem;
    }

    .carousel-item {
        height: 200px;
    }

    .carousel-caption {
        padding: 1rem;
    }
}

    </style>

    @php
        $isRegistered = \App\Models\SkYouthForm::where('user_id', auth()->id())->exists();
        $newAnnouncement = \App\Models\Announcement::where('is_seen', false)->latest()->first();
        $newEvent = \App\Models\Event::where('is_seen', false)->latest()->first();

        // Fetch all documents with images
        $documents = \App\Models\Document::whereNotNull('image')->get();
        $allImages = [];

        // Extract all images from the documents
        foreach($documents as $doc) {
            $images = $doc->image ? json_decode($doc->image) : [];
            foreach($images as $img) {
                $allImages[] = [
                    'path' => $img,
                    'title' => $doc->title,
                    'date' => $doc->created_at
                ];
            }
        }
    @endphp

    @if(auth()->user()->status == 'accepted')


        <!-- Dashboard content for accepted users -->

        <div class="container mt-4 d-flex justify-content-center">
            <div class="d-flex flex-wrap gap-4 align-items-center p-3">
                <!-- SK Officials Button - Using SK Blue -->
                <a href="{{ route('users.sk-officials') }}" class="btn p-3 rounded-4 border-0 position-relative casual-btn"
                style="background-color: #E6F0FA; min-width: 200px; height: 170px;">

                    <div class="position-absolute top-0 start-0 m-2">
                        <img src="{{ asset("images/sk-ranget.png") }}" alt="SK Logo" style="width: 30px; height: 30px; object-fit: contain;">
                    </div>

                    <div class="d-flex flex-column align-items-center justify-content-center h-100">
                        <i class="fas fa-users fa-3x mb-2" style="color: #0055A5;"></i>
                        <span class="fw-bold" style="color: #003366;">SK OFFICIALS</span>
                        <small class="text-muted" style="color:  #0055A5!important;">Sangguniang Kabataan Officials</small>
                    </div>

                    <div class="position-absolute top-0 end-0 m-2">
                        <i class="fas fa-arrow-right fa-xs" style="color: #0055A5;"></i>
                    </div>
                </a>

                <!-- Kabataan List Button - Using SK Red -->
                <a href="{{ route('users.kabataan-list') }}" class="btn p-3 rounded-4 border-0 position-relative casual-btn"
                style="background-color: #FFEEEE; min-width: 200px; height: 170px;">

                    <div class="position-absolute top-0 start-0 m-2">
                        <img src="{{ asset("images/sk-ranget.png") }}" alt="SK Logo" style="width: 30px; height: 30px; object-fit: contain;">
                    </div>

                    <div class="d-flex flex-column align-items-center justify-content-center h-100">
                        <i class="fas fa-user-friends fa-3x mb-2" style="color: #D62B2B;"></i>
                        <span class="fw-bold" style="color: #A51C1C;">KABATAAN</span>
                        <small class="text-muted" style="color:  #D62B2B!important;">Sangguniang Kabataan</small>
                    </div>

                    <div class="position-absolute top-0 end-0 m-2">
                        <i class="fas fa-arrow-right fa-xs" style="color: #D62B2B;"></i>
                    </div>
                </a>

                <!-- Announcement Button - Using SK Yellow -->
                <div class="d-flex flex-wrap gap-4 align-items-center p-3">
                    <a href="{{ route('users.announcement') }}" class="btn p-3 rounded-4 border-0 position-relative casual-btn"
                    style="background: linear-gradient(to bottom right, #FFF8E6 0%, #FFECB3 100%); min-width: 200px; height: 170px;">

                        <div class="position-absolute top-0 start-0 m-2">
                            <img src="{{ asset("images/sk-ranget.png") }}" alt="SK Logo" style="width: 30px; height: 30px; object-fit: contain;">
                        </div>

                        <div class="d-flex flex-column align-items-center justify-content-center h-100">
                            <div class="position-relative">
                                <i class="fas fa-calendar-alt fa-3x mb-2" style="color: #FFC107;"></i>
                            </div>
                            <span class="fw-bold mt-1" style="color: #D62B2B; font-size: 1.1rem;">ANNOUNCEMENTS</span>
                            <small class="text-muted" style="color: #FF8F00!important;">Important Announcements</small>
                        </div>

                        <div class="position-absolute top-0 end-0 m-2">
                            <i class="fas fa-arrow-right" style="color: #FF8F00;"></i>
                        </div>

                        {{-- alert for new uploaded announcement --}}
                        @if($newAnnouncement && !$newAnnouncement->is_seen)
                            <div class="position-absolute top-0 end-0 p-2">
                                <span class="badge bg-danger">New!</span>
                            </div>
                        @endif
                    </a>
                </div>

                <!-- Events Button -->
                <div class="d-flex flex-wrap gap-4 align-items-center p-3">
                    <a href="{{ route('users.events') }}" class="btn p-3 rounded-4 border-0 position-relative casual-btn"
                    style="background: linear-gradient(to bottom right, #E6FFF7 0%, #B2F2E5 100%); min-width: 200px; height: 170px;">
                        <div class="position-absolute top-0 start-0 m-2">
                            <img src="{{ asset("images/sk-ranget.png") }}" alt="SK Logo" style="width: 30px; height: 30px; object-fit: contain;">
                        </div>
                        <div class="d-flex flex-column align-items-center justify-content-center h-100">
                            <i class="fas fa-calendar-check fa-3x mb-2" style="color: #00B894;"></i>
                            <span class="fw-bold mt-1" style="color: #007A63; font-size: 1.1rem;">EVENTS</span>
                            <small class="text-muted" style="color: #00B894!important;">Upcoming Activities</small>
                        </div>
                        <div class="position-absolute top-0 end-0 m-2">
                            <i class="fas fa-arrow-right fa-xs" style="color: #00B894;"></i>
                        </div>

                        {{-- alert for new uploaded events --}}
                        @if($newEvent && !$newEvent->is_seen)
                            <div class="position-absolute top-0 end-0 p-2">
                                <span class="badge bg-danger">New!</span>
                            </div>
                        @endif
                    </a>
                </div>
            </div>
        </div>

        @if(count($allImages) > 0)
<div class="container mt-4">
    @php
        // Group images by document title
        $groupedImages = [];
        foreach($allImages as $image) {
            $groupedImages[$image['title']][] = $image;
        }
    @endphp

    <div class="carousel-container">
        <div class="carousel-header">
            <h3 class="carousel-title">
                <i class="fas fa-images"></i>
                <span>Documentations</span>
            </h3>
            <div class="carousel-controls">
                <button class="carousel-control-button" id="pause-carousel">
                    <i class="fas fa-pause"></i>
                </button>
                <button class="carousel-control-button" id="random-next">
                    <i class="fas fa-random"></i>
                </button>
            </div>
        </div>
        <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @php $slideIndex = 0; @endphp
                @foreach($groupedImages as $title => $images)
                    @foreach($images as $index => $image)
                        <button type="button" data-bs-target="#imageCarousel" data-bs-slide-to="{{ $slideIndex }}"
                            class="{{ $slideIndex == 0 ? 'active' : '' }}" aria-label="Slide {{ $slideIndex + 1 }}"></button>
                        @php $slideIndex++; @endphp
                    @endforeach
                @endforeach
            </div>
            <div class="carousel-inner">
                @php $slideIndex = 0; @endphp
                @foreach($groupedImages as $title => $images)
                    @foreach($images as $index => $image)
                        <div class="carousel-item {{ $slideIndex == 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $image['path']) }}" class="d-block w-100 img-fluid" alt="{{ $title }}">
                            <div class="carousel-caption d-none d-md-block">
                                <h5><i class="fas fa-file-image me-1"></i> {{ $title }}</h5>
                                <p>{{ $image['date']->format('F d, Y') }} — <span class="text-warning">Image {{ $index + 1 }}</span> of {{ count($images) }}</p>
                            </div>
                        </div>
                        @php $slideIndex++; @endphp
                    @endforeach
                @endforeach
            </div>
            <button class="carousel-nav prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                <i class="fas fa-chevron-left"></i>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-nav next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                <i class="fas fa-chevron-right"></i>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <div class="carousel-thumbnails"></div>
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap carousel
    const carousel = new bootstrap.Carousel(document.getElementById('imageCarousel'), {
        interval: 5000,
        wrap: true
    });

    // Pause/play functionality
    const pauseButton = document.getElementById('pause-carousel');
    let isPlaying = true;

    pauseButton.addEventListener('click', function() {
        if (isPlaying) {
            carousel.pause();
            pauseButton.innerHTML = '<i class="fas fa-play"></i>';
            isPlaying = false;
        } else {
            carousel.cycle();
            pauseButton.innerHTML = '<i class="fas fa-pause"></i>';
            isPlaying = true;
        }
    });

    // Random next functionality
    const randomNextButton = document.getElementById('random-next');
    const totalSlides = document.querySelectorAll('.carousel-item').length;

    randomNextButton.addEventListener('click', function() {
        // Get current active slide index
        const currentIndex = Array.from(document.querySelectorAll('.carousel-item'))
            .findIndex(item => item.classList.contains('active'));

        // Generate random index that's different from current
        let randomIndex;
        do {
            randomIndex = Math.floor(Math.random() * totalSlides);
        } while (randomIndex === currentIndex && totalSlides > 1);

        // Go to random slide
        carousel.to(randomIndex);
    });

    // Generate thumbnails
    function generateThumbnails() {
        const carouselItems = document.querySelectorAll('.carousel-item');
        const thumbnailContainer = document.querySelector('.carousel-thumbnails');

        if (!thumbnailContainer) return;

        carouselItems.forEach((item, index) => {
            const imgSrc = item.querySelector('img').src;
            const thumbnail = document.createElement('div');
            thumbnail.className = `carousel-thumbnail ${index === 0 ? 'active' : ''}`;
            thumbnail.innerHTML = `<img src="${imgSrc}" alt="Thumbnail">`;

            thumbnail.addEventListener('click', () => {
                carousel.to(index);
            });

            thumbnailContainer.appendChild(thumbnail);
        });
    }

    // Update active thumbnail when carousel slides
    document.getElementById('imageCarousel').addEventListener('slid.bs.carousel', function(event) {
        const activeIndex = event.to;
        document.querySelectorAll('.carousel-thumbnail').forEach((thumb, index) => {
            if (index === activeIndex) {
                thumb.classList.add('active');
            } else {
                thumb.classList.remove('active');
            }
        });
    });

    // Generate thumbnails after carousel is loaded
    generateThumbnails();
});
</script>

        @elseif(auth()->user()->status == 'pending')
        <!-- Only show pending alert and logout access -->
        <div class="container mt-5">
            <div class="alert alert-warning text-center p-4 show">
                <h4 class="alert-heading">Registration Pending</h4>
                <p>Your registration is still under review. You'll have full access once approved.</p>
                <hr>
                <p class="mb-0">Please check back later or contact the administrator for more information.</p>
            </div>
        </div>
    @elseif(auth()->user()->status == 'rejected')
        <!-- Only show rejected alert and logout access -->
        <div class="container mt-5">
            <div class="alert alert-danger text-center p-4 show">
                <h4 class="alert-heading">Registration Rejected</h4>
                <p>Sorry, your registration was not approved.</p>
                <hr>
                <p class="mb-0">Please contact the administrator at <a href="mailto:admin@example.com">admin@example.com</a> for more information.</p>
            </div>
        </div>
    @endif

       
    <style>
        .modal-backdrop {
            display: none !important;
        }
    </style>
</x-app-layout>
