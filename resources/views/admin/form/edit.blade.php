<x-app-layout>
    {{-- SweetAlert2 script --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Announcements') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Edit Announcement</h3>


    <form action="{{ route('admin.announcement.update', $announcements->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mt-4">
            <label class="block">Title</label>
            <input type="text" name="title" value="{{ $announcements->title }}" class="w-full p-2 border rounded">
        </div>

        <div class="mt-4">
            <label class="block">Content</label>
            <textarea name="content" class="w-full p-2 border rounded">{{ $announcements->content }}</textarea>
        </div>

        <div class="mt-4">
            <button type="submit" class="bg-primary text-black px-4 py-2 rounded">Update</button>
            <a href="{{ route('admin.announcement') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
        </div>
    </form>
</div>



</div>
</div>
</div>
</div>

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
</x-app-layout>
