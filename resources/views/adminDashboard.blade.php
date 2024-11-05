@extends('layouts.app')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Poppins', sans-serif;
    }

    h2,
    h6 {
        font-weight: 600;
    }

    .card {
        transition: transform 0.2s, box-shadow 0.2s;
        border-radius: 12px;
        overflow: hidden;
        margin-left: 30px;
        margin-right: 30px;
    }

    .card:hover {
        transform: scale(1.03);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .chart-container {
        position: relative;
        height: 400px;
        width: 100%;
        overflow-y: auto;
    }

    .card-header {
        font-size: 1.2rem;
        font-weight: bold;
    }
</style>

@section('content')
<div class="container-fluid py-1">
    <div class="row justify-content-center mb-4">
        <div class="col-12 text-center">
            <h2 class="text-primary font-weight-bold">{{ __('Dashboard') }}</h2>
        </div>
    </div>

    <div class="row justify-content-center mb-2">
        <div class="col-md-5 mb-3">
            <div class="card shadow-sm text-center border-0">
                <div class="card-body bg-light">
                    <h6 class="text-muted">จำนวนรถทั้งหมด/คัน</h6>
                    <p class="h1 font-weight-bold text-primary">{{ $vehicleCount ?? 'Loading...' }}</p>
                    <i class="fas fa-car fa-3x text-primary"></i>
                </div>
            </div>
        </div>
        <div class="col-md-5 mb-3">
            <div class="card shadow-sm text-center border-0">
                <div class="card-body bg-light">
                    <h6 class="text-muted">จำนวนบุคลากรทั้งหมด/คน</h6>
                    <p class="h1 font-weight-bold text-primary">{{ $userCount ?? 'Loading...' }}</p>
                    <i class="fas fa-users fa-3x text-primary"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 text-end mb-3">
        <form method="GET" action="{{ route('admin.dashboard') }}" id="dashboardForm">
            <div class="form-group">
                <label class="font-weight-bold">เลือกประเภทการแสดงผล:</label>
                <ul class="nav nav-tabs justify-content-end">
                    <li class="nav-item">
                        <a class="nav-link {{ $viewType == 'month' ? 'active' : '' }}" href="#"
                            onclick="setViewType('month');">รายเดือน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $viewType == 'quarter' ? 'active' : '' }}" href="#"
                            onclick="setViewType('quarter');">รายไตรมาส</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $viewType == 'year' ? 'active' : '' }}" href="#"
                            onclick="setViewType('year');">ทั้งปี</a>
                    </li>
                </ul>
                <input type="hidden" name="view_type" id="view_type" value="{{ $viewType }}">

                <div id="monthYearSelection"
                    class="d-flex align-items-center justify-content-end mt-3 {{ $viewType === 'year' ? 'd-none' : '' }}">
                    @if ($viewType === 'quarter')
                        <label for="quarter" class="font-weight-bold mr-2 mt-2">เลือกไตรมาส:</label>
                        <select name="quarter" id="quarter" class="form-control d-inline-block w-auto mr-3"
                            onchange="submitForm()">
                            @for ($q = 1; $q <= 4; $q++)
                                <option value="{{ $q }}" {{ $quarter == $q ? 'selected' : '' }} {{ $q == ceil(date('n') / 3) ? 'style=font-weight:bold;color:red;' : '' }}>
                                    ไตรมาส {{ $q }}
                                    @if ($q === 1)
                                        (มกราคม - มีนาคม)
                                    @elseif ($q === 2)
                                        (เมษายน - มิถุนายน)
                                    @elseif ($q === 3)
                                        (กรกฎาคม - กันยายน)
                                    @elseif ($q === 4)
                                        (ตุลาคม - ธันวาคม)
                                    @endif
                                    @if ($q == ceil(date('n') / 3))
                                        (ปัจจุบัน)
                                    @endif
                                </option>
                            @endfor
                        </select>
                    @else
                        <label for="month" class="font-weight-bold mr-2 mt-2">เลือกเดือน:</label>
                        <select name="month" id="month" class="form-control d-inline-block w-auto mr-3"
                            onchange="submitForm()">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $i == $month ? 'selected' : '' }} {{ $i == date('n') ? 'style=font-weight:bold;color:red;' : '' }}>
                                    {{ ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'][$i - 1] }}{{ $i == date('n') ? ' (ปัจจุบัน)' : '' }}
                                </option>
                            @endfor
                        </select>
                    @endif
                </div>

                <div class="d-flex align-items-center justify-content-end mt-3">
                    <label for="year" class="font-weight-bold mr-2 mt-2">เลือกปี:</label>
                    <select name="year" id="year" class="form-control d-inline-block w-auto" onchange="submitForm()">
                        @foreach ($yearsWithData as $year)
                            <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }} {{ $year == date('Y') ? 'style=font-weight:bold;color:red;' : '' }}>
                                {{ $year + 543 }} {{ $year == date('Y') ? ' (ปัจจุบัน)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>

    <style>
        .nav-tabs .nav-link {
            color: #e0e0e0;
            background-color: #007bff;
            /* สีพื้นหลังของแท็บที่ไม่ได้เลือก */
            border-radius: 5px;
        }

        .nav-tabs .nav-link.active {
            background-color: #ffc107;
            color: white;
            font-weight: bold;
        }
    </style>
    <script>
        function setViewType(type) {
            document.getElementById('view_type').value = type;
            document.getElementById('dashboardForm').submit();
        }
    </script>

    <div class="row">
        @foreach ([['title' => 'จำนวนการขออนุญาตของบุคลากร', 'id' => 'requestChart', 'bgColor' => 'bg-primary'], ['title' => 'จำนวนการขออนุญาตตามส่วนงาน', 'id' => 'divisionChart', 'bgColor' => 'bg-danger'], ['title' => 'จำนวนการขออนุญาตตามประเภทงาน', 'id' => 'workChart', 'bgColor' => 'bg-success'], ['title' => 'จำนวนการขออนุญาตตามประเภทของรถ', 'id' => 'carTypeChart', 'bgColor' => 'bg-warning']] as $chart)
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header {{ $chart['bgColor'] }} text-white text-center h5">{{ $chart['title'] }}</div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="{{ $chart['id'] }}"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    function toggleMonthYear(select) {
        const monthYearSelection = document.getElementById('monthYearSelection');
        monthYearSelection.classList.toggle('d-none', select.value === 'year');
    }

    // Function to submit the form
    function submitForm() {
        document.getElementById('dashboardForm').submit();
    }

    // Function to create charts
    function createChart(ctx, labels, datasets, timePeriod) {
        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            color: (context) => context.index % 2 === 0 ? 'rgba(200, 200, 200, 0.2)' : 'rgba(255, 255, 255, 0.2)'
                        },
                        title: {
                            display: true,
                            text: 'ครั้ง'
                        },
                        ticks: {
                            callback: function (value) {
                                return Number.isInteger(value) ? value : '';
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        maxTicksLimit: 10,
                        ticks: {
                            font: {
                                size: labels.length > 10 ? 10 : 12
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            color: '#333'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.raw + ' ครั้ง';
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: timePeriod, // Only show the time period
                        font: {
                            size: 22,
                            weight: 'bold'
                        },
                        color: '#333'
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1000,
                    easing: 'easeOutExpo'
                },
            }
        });
    }

    // สร้างกราฟจำนวนการขออนุญาตของบุคลากรที่รวม approvedData
    createChart(document.getElementById('requestChart').getContext('2d'), @json($labels), [
        {
            label: 'จำนวนการขออนุญาตทั้งหมด',
            data: @json($data),
            backgroundColor: 'rgba(0, 0, 255, 0.6)',
            borderColor: 'rgba(0, 0, 255, 1)',
            borderWidth: 2,
            borderRadius: 5,
        },
        {
            label: 'จำนวนที่ได้รับการอนุมัติ',
            data: @json($approvedData),
            backgroundColor: 'rgba(0, 255, 0, 0.6)',
            borderColor: 'rgba(0, 255, 0, 1)',
            borderWidth: 2,
            borderRadius: 5,
        }
    ], `{{ $viewType === 'month' ? ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'][$month - 1] . ' ปี ' . ($selectedYear + 543) : ($viewType === 'quarter' ? 'ไตรมาส ' . $quarter . ' ปี ' . ($selectedYear + 543) : 'ปี ' . ($selectedYear + 543)) }}`);

    // สร้างกราฟจำนวนการขออนุญาตตามส่วนงานที่รวม approvedDivisionData
    createChart(document.getElementById('divisionChart').getContext('2d'), @json($divisionLabels), [
        {
            label: 'จำนวนการขออนุญาตตามส่วนงาน',
            data: @json($divisionData),
            backgroundColor: 'rgba(255, 99, 132, 0.6)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 2,
            borderRadius: 5,
        },
        {
            label: 'จำนวนที่ได้รับการอนุมัติ',
            data: @json($approvedDivisionData),
            backgroundColor: 'rgba(0, 255, 0, 0.6)',
            borderColor: 'rgba(0, 255, 0, 1)',
            borderWidth: 2,
            borderRadius: 5,
        }
    ], `{{ $viewType === 'month' ? ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'][$month - 1] . ' ปี ' . ($selectedYear + 543) : ($viewType === 'quarter' ? 'ไตรมาส ' . $quarter . ' ปี ' . ($selectedYear + 543) : 'ปี ' . ($selectedYear + 543)) }}`);

    // สร้างกราฟอื่นๆ
    createChart(document.getElementById('workChart').getContext('2d'), @json($workLabels), [{
        label: 'จำนวนการขออนุญาตตามประเภทงาน',
        data: @json($workData),
        backgroundColor: 'rgba(75, 192, 192, 0.6)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 2,
        borderRadius: 5,
    }], `{{ $viewType === 'month' ? ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'][$month - 1] . ' ปี ' . ($selectedYear + 543) : ($viewType === 'quarter' ? 'ไตรมาส ' . $quarter . ' ปี ' . ($selectedYear + 543) : 'ปี ' . ($selectedYear + 543)) }}`);

    createChart(document.getElementById('carTypeChart').getContext('2d'), @json($carTypeLabels), [{
        label: 'จำนวนการขออนุญาตตามประเภทของรถ',
        data: @json($carTypeData),
        backgroundColor: 'rgba(255, 206, 86, 0.6)',
        borderColor: 'rgba(255, 206, 86, 1)',
        borderWidth: 2,
        borderRadius: 5,
    }], `{{ $viewType === 'month' ? ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'][$month - 1] . ' ปี ' . ($selectedYear + 543) : ($viewType === 'quarter' ? 'ไตรมาส ' . $quarter . ' ปี ' . ($selectedYear + 543) : 'ปี ' . ($selectedYear + 543)) }}`);
</script>



@endsection