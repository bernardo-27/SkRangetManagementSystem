<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold text-dark">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="container py-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="h5 font-weight-bold mb-4">Dashboard Statistics</h3>

                <div class="row">
                    <!-- Total Users -->
                    <div class="col-md-4 mb-4">
                        <div class="card bg-primary bg-opacity-10 border-0 rounded-lg shadow-sm">
                            <div class="card-body text-center">
                                <h4 class="h6 font-weight-bold text-primary">Total Youth Registered</h4>
                                <p class="h3 font-weight-bold">{{ $totalUsers }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Voters -->
                    <div class="col-md-4 mb-4">
                        <div class="card bg-success bg-opacity-10 border-0 rounded-lg shadow-sm">
                            <div class="card-body text-center">
                                <h4 class="h6 font-weight-bold text-success">Total Youth Voters</h4>
                                <p class="h3 font-weight-bold">{{ $totalVoters }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Non-Voters -->
                    <div class="col-md-4 mb-4">
                        <div class="card bg-danger bg-opacity-10 border-0 rounded-lg shadow-sm">
                            <div class="card-body text-center">
                                <h4 class="h6 font-weight-bold text-danger">Total Youth Non-Voters</h4>
                                <p class="h3 font-weight-bold">{{ $totalNonVoters }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="ageFilter" class="form-label">Filter by Age:</label>
                        <select id="ageFilter" class="form-select">
                            <option value="all">All Ages</option>
                            <option value="15-18">15-18</option>
                            <option value="19-22">19-22</option>
                            <option value="23-30">23-30</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="genderFilter" class="form-label">Filter by Gender:</label>
                        <select id="genderFilter" class="form-select">
                            <option value="all">All</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                </div>


                <!-- Gender Distribution & Chart -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card bg-secondary bg-opacity-10 border-0  rounded-lg shadow-sm h-30">
                            <div class="card-body text-center">
                                <h4 class="h6 font-weight-bold text-secondary">Gender Distribution</h4>
                                <p id="maleCount">Male: <strong>{{ $maleCount }}</strong></p>
                                <p id="femaleCount">Female: <strong>{{ $femaleCount }}</strong></p>
                            </div>
                        </div>

                        <div class="col-md-12 mt-3 mb-2">
                            <label for="voterFilter" class="form-label">Filter by Voter Status:</label>
                            <select id="voterFilter" class="form-select">
                                <option value="all">All</option>
                                <option value="voters">Voters</option>
                                <option value="non-voters">Non-Voters</option>
                            </select>
                        </div>
                    <!-- Voters vs Non-Voters Chart -->
                    <div class="col-md-12">
                        <div class="card shadow-sm ">
                            <div class="card-body d-flex justify-content-center" >
                                <h4 class="h6 font-weight-bold mb-4">Voters vs Non-Voters</h4>
                                <div style="width: 100%; max-width: 600px;">
                                    <canvas id="votersChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex justify-content-center">
                                <div style="width: 100%; max-width: 400px;">
                                    <canvas id="genderChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
var originalData = @json($ageGroups);
var ctx = document.getElementById('genderChart').getContext('2d');
var votersCtx = document.getElementById('votersChart').getContext('2d');

// Gender distribution chart
var genderChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Male', 'Female'],
        datasets: [{
            data: [{{ $maleCount }}, {{ $femaleCount }}],
            backgroundColor: ['#36A2EB', '#FF6384']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Voters vs Non-Voters chart
var votersChart = new Chart(votersCtx, {
    type: 'bar',
    data: {
        labels: ['Voters', 'Non-Voters'],
        datasets: [{
            label: 'Voters Count',
            data: [{{ $totalVoters }}, {{ $totalNonVoters }}],
            backgroundColor: ['#36A2EB', '#FF6384'],
            borderColor: ['#4CAF50', '#F44336'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

document.getElementById('ageFilter').addEventListener('change', updateChart);
document.getElementById('genderFilter').addEventListener('change', updateChart);
document.getElementById('voterFilter').addEventListener('change', updateChart); // Voter filter

function updateChart() {
    var selectedAge = document.getElementById('ageFilter').value;
    var selectedGender = document.getElementById('genderFilter').value;
    var selectedVoterStatus = document.getElementById('voterFilter').value; // Get selected voter status

    var filteredMale = {{ $maleCount }};
    var filteredFemale = {{ $femaleCount }};
    var filteredVoters = {{ $totalVoters }};
    var filteredNonVoters = {{ $totalNonVoters }};

    if (selectedAge !== "all") {
        var [minAge, maxAge] = selectedAge.split('-').map(Number);
        filteredMale = originalData.male[selectedAge] || 0;
        filteredFemale = originalData.female[selectedAge] || 0;
    }

    if (selectedGender === "male") {
        filteredFemale = 0;
    } else if (selectedGender === "female") {
        filteredMale = 0;
    }

    if (selectedVoterStatus === "voters") {
        filteredNonVoters = 0;
    } else if (selectedVoterStatus === "non-voters") {
        filteredVoters = 0;
    }

    genderChart.data.datasets[0].data = [filteredMale, filteredFemale];
    genderChart.update();

    votersChart.data.datasets[0].data = [filteredVoters, filteredNonVoters];
    votersChart.update();

    document.getElementById('maleCount').innerHTML = `Male: <strong>${filteredMale}</strong>`;
    document.getElementById('femaleCount').innerHTML = `Female: <strong>${filteredFemale}</strong>`;
    document.getElementById('voterCount').innerHTML = `Voters: <strong>${filteredVoters}</strong>`;
    document.getElementById('nonVoterCount').innerHTML = `Non-Voters: <strong>${filteredNonVoters}</strong>`;
}

    </script>
</x-app-layout>
