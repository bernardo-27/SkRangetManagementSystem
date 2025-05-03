<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons (Optional) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


<div class="container py-5">
    <!-- Header Section -->
    <div class="text-center mb-5">
        @if($official->photo)
            <img src="{{ asset($official->photo) }}" alt="{{ $official->name }}"
                 class="img-fluid rounded-circle shadow-lg border border-3 border-primary"
                 style="width: 180px; height: 180px; object-fit: cover;">
        @endif
        <h1 class="mt-4 fw-bold">{{ $official->name }}</h1>
        <p class="text-muted fs-5">{{ $official->position }}</p>
    </div>

    <!-- Information Section -->
    <div class="row g-4">
        <!-- Term Information -->
        <div class="col-md-6">
            <div class="p-4 border rounded shadow-sm bg-light">
                <h5 class="fw-bold mb-3 text-primary">Term Information</h5>
                <p class="mb-1"><strong>Start Date:</strong></p>
                <p class="text-muted">{{ \Carbon\Carbon::parse($official->term_start)->format('F d, Y') }}</p>
                <p class="mb-1"><strong>End Date:</strong></p>
                <p class="text-muted">{{ \Carbon\Carbon::parse($official->term_end)->format('F d, Y') }}</p>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="col-md-6">
            <div class="p-4 border rounded shadow-sm bg-light">
                <h5 class="fw-bold mb-3 text-primary">Contact Information</h5>
                @if($official->email)
                    <p class="mb-1"><strong>Email:</strong></p>
                    <p><a href="mailto:{{ $official->email }}" class="text-decoration-none text-primary">{{ $official->email }}</a></p>
                @endif
                @if($official->phone)
                    <p class="mb-1"><strong>Phone:</strong></p>
                    <p><a href="tel:{{ $official->phone }}" class="text-decoration-none text-primary">{{ $official->phone }}</a></p>
                @endif
            </div>
        </div>

        <!-- Personal Information -->
        <div class="col-md-6">
            <div class="p-4 border rounded shadow-sm bg-light">
                <h5 class="fw-bold mb-3 text-primary">Personal Information</h5>
                @if($official->birthdate)
                    <p class="mb-1"><strong>Birthdate:</strong></p>
                    <p class="text-muted">{{ \Carbon\Carbon::parse($official->birthdate)->format('F d, Y') }}</p>
                @endif
                @if($official->gender)
                    <p class="mb-1"><strong>Gender:</strong></p>
                    <p class="text-muted">{{ ucfirst($official->gender) }}</p>
                @endif
            </div>
        </div>

        <!-- Achievements -->
        @if($official->achievements)
            <div class="col-md-6">
                <div class="p-4 border rounded shadow-sm bg-light">
                    <h5 class="fw-bold mb-3 text-primary">Achievements</h5>
                    <p class="text-muted">{{ $official->achievements }}</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Back to List Button -->
    {{-- <div class="text-center mt-5">
        <a href="{{ url('/users/sk-officials') }}" class="btn btn-outline-primary btn-lg px-4">Back to List</a>
    </div> --}}
</div>
