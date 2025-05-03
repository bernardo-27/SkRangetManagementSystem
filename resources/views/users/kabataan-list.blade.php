<x-app-layout>
    <div class="container mt-4">
        <h2 class="mb-4">Youth</h2>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Gender</th>
                        <th>Street / Zone</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kabataan as $index => $youth)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $youth->full_name }}</td>
                            <td>{{ $youth->gender }}</td>
                            <td>{{ $youth->address }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
