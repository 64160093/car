@extends('layouts.app')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@section('content')
<div class="container-fluid p-3">
    <div class="row justify-content-center mb-2">
        <div class="col-12 ">
            <h2 class="text-primary text-center font-weight-bold">{{ __('Dashboard') }}</h2>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-10">
            <div class="row text-center mb-4">
                <div class="col-md-6">
                    <div class="p-3 border rounded shadow-sm bg-light">
                        <h6 class="mb-1">จำนวนประเภทรถ</h6>
                        <p class="h3">{{ $vehicleCount ?? 'Loading...' }}</p>
                        <i class="fas fa-car fa-lg text-primary"></i>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border rounded shadow-sm bg-light">
                        <h6 class="mb-1">จำนวนบุคลากรทั้งหมด</h6>
                        <p class="h3">{{ $userCount ?? 'Loading...' }}</p>
                        <i class="fas fa-users fa-lg text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        @foreach ([['title' => 'การขออนุญาตใช้รถของแต่ละบุคลากร', 'id' => 'requestChart', 'bgColor' => 'bg-info'], ['title' => 'จำนวนการขออนุญาตตามส่วนงาน', 'id' => 'divisionChart', 'bgColor' => 'bg-danger'], ['title' => 'จำนวนการขออนุญาตตามประเภทงาน', 'id' => 'workChart', 'bgColor' => 'bg-success'], ['title' => 'จำนวนการขออนุญาตตามประเภทของรถ', 'id' => 'carTypeChart', 'bgColor' => 'bg-warning']] as $chart)
            <div class="col-md-6 mt-4">
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header {{ $chart['bgColor'] }} text-white text-center h5">{{ $chart['title'] }}</div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height:30vh; width:100%;">
                            <canvas id="{{ $chart['id'] }}"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<script>
    // กราฟแท่งสำหรับการขออนุญาตของแต่ละบุคลากร
    const ctx = document.getElementById('requestChart').getContext('2d');
    const requestChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'จำนวนการขออนุญาต',
                data: @json($data),
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            return Number.isInteger(value) ? value : '';
                        }
                    }
                },
                y: {
                    beginAtZero: true
                }
            },
            responsive: true,
            maintainAspectRatio: false,
        }
    });

    // กราฟแท่งสำหรับการขออนุญาตตามส่วนงาน
    const ctxDivision = document.getElementById('divisionChart').getContext('2d');
    const divisionChart = new Chart(ctxDivision, {
        type: 'bar',
        data: {
            labels: @json($divisionLabels),
            datasets: [{
                label: 'จำนวนการขออนุญาตตามส่วนงาน',
                data: @json($divisionData),
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            return Number.isInteger(value) ? value : '';
                        }
                    }
                },
                y: {
                    beginAtZero: true
                }
            },
            responsive: true,
            maintainAspectRatio: false,
        }
    });

    // กราฟแท่งสำหรับการขออนุญาตตามประเภทงาน
    const ctxWork = document.getElementById('workChart').getContext('2d');
    const workChart = new Chart(ctxWork, {
        type: 'bar',
        data: {
            labels: @json($workLabels),
            datasets: [{
                label: 'จำนวนการขออนุญาตตามประเภทงาน',
                data: @json($workData),
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            return Number.isInteger(value) ? value : '';
                        }
                    }
                },
                y: {
                    beginAtZero: true
                }
            },
            responsive: true,
            maintainAspectRatio: false,
        }
    });

    // กราฟแท่งสำหรับการขออนุญาตตามประเภทของรถ
    const ctxCarType = document.getElementById('carTypeChart').getContext('2d');
    const carTypeChart = new Chart(ctxCarType, {
        type: 'bar',
        data: {
            labels: @json($carTypeLabels),
            datasets: [{
                label: 'จำนวนการขออนุญาตตามประเภทของรถ',
                data: @json($carTypeData),
                backgroundColor: 'rgba(255, 206, 86, 0.6)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            return Number.isInteger(value) ? value : '';
                        }
                    }
                },
                y: {
                    beginAtZero: true
                }
            },
            responsive: true,
            maintainAspectRatio: false,
        }
    });
</script>

@endsection