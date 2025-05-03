<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 text-dark">
            {{ __('Edit Documents') }}
        </h2>
    </x-slot>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="mb-3">Edit Event</h3>

                        <form method="POST" action="{{ route('admin.documents.update', $document->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" id="title" value="{{ old('title', $document->title) }}" required class="form-control">
                            </div>


                            <!-- Display existing images -->
                            <div class="mb-3">
                                <label class="form-label">Current Images</label>
                                <div class="row g-2">
                                    @foreach (json_decode($document->image, true) ?? [] as $img)
                                        <div class="col-md-3">
                                            <img src="{{ asset('storage/' . $img) }}" class="img-fluid rounded border" style="max-height: 100px;">
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Upload New Images</label>
                                <input type="file" name="image[]" id="image" class="form-control" multiple>
                            </div>

                            <!-- Preview selected images -->
                            <div class="mb-3">
                                <label class="form-label">Image Preview</label>
                                <div id="preview-container" class="d-flex flex-wrap gap-2"></div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



    {{-- Image preview script --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let previewContainer = document.getElementById('preview-container');
            let imageInput = document.getElementById('image');

            imageInput.addEventListener('change', function (documents) {
                previewContainer.innerHTML = '';

                Array.from(event.target.files).forEach((file) => {
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        let img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('img-thumbnail', 'border', 'me-2');
                        img.style.width = "150px";
                        img.style.height = "100px";

                        previewContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            });
        });
    </script>
</x-app-layout>
