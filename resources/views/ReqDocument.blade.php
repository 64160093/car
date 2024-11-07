@extends('layouts.app')

@section('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Request Document</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>


@endsection

@section('content')
<div class="container mt-1">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">{{ __('แบบฟอร์มขออนุญาตใช้ยานพาหนะ') }}</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('reqdocument.store') }}" enctype="multipart/form-data">
                @csrf
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- วันที่ทำเรื่อง (Moved to top-right) -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <h5>{{ __('รายละเอียดการเดินทาง') }}</h5>
                        <label for="user_name">{{ __('ชื่อผู้ขอ : ') }}</label>
                        <span id="user_name" class="font-weight-bold">
                            {{ Auth::user()->name }} {{ Auth::user()->lname }}
                        </span><br>
                        <label for="division_name">{{ __('ส่วนงาน : ') }}</label>
                        <span id="division_name" class="font-weight-bold">
                            @if (Auth::user()->division)
                                {{ Auth::user()->division->division_name }}
                            @else
                                {{ __(' - ') }}
                            @endif
                        </span><br>
                        <label for="department_name">{{ __('ฝ่ายงาน : ') }}</label>
                        <span id="department_name" class="font-weight-bold">
                            @if (Auth::user()->department)
                                {{ Auth::user()->department->department_name }}
                            @else
                                {{ __(' - ') }}
                            @endif
                        </span>
                    </div>

                    <div class="col-md-4 text-right">
                        <div class="form-group">
                            <label for="reservation_date">{{ __('วันที่ทำเรื่อง') }}</label>
                            <input type="date"
                                class="form-control no-border text-center @error('reservation_date') is-invalid @enderror"
                                id="reservation_date" name="reservation_date" value="{{ old('reservation_date') }}"
                                readonly required>
                            @error('reservation_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                    </div>
                </div>

                <!-- Dropdown เลือกรายชื่อพนักงาน -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="employee">{{ __('เลือกผู้ร่วมเดินทาง') }}</label>
                            <select class="form-control" id="employee_select">
                                <option value="">{{ __('เลือกผู้ร่วมเดินทาง') }}</option>
                                @foreach ($user as $users)
                                    @if ($users->id !== Auth::user()->id && !$users->isAdmin() && !in_array($users->role_id, [11, 12]))
                                        <!-- ใช้ isAdmin() ในการตรวจสอบ และเช็ค role_id -->
                                        <option value="{{ $users->id }}" data-id="{{ $users->id }}">
                                            {{ $users->name }} {{ $users->lname }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>


                <!-- ฟิลด์ผู้ร่วมเดินทาง -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="companion_name">{{ __('ผู้ร่วมเดินทาง') }}</label>
                            <div class="form-control" id="companion_name" style="min-height: 100px;"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sum_companion">{{ __('จำนวนผู้ร่วมเดินทาง') }}</label>
                            <input type="number"
                                class="form-control text-center @error('sum_companion') is-invalid @enderror"
                                id="sum_companion" name="sum_companion" value="0" required readonly>
                            @error('sum_companion')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <input type="hidden" id="companions_hidden" name="companion_name">

                <!-- ผู้ควบคุมรถ -->
                <div class="form-group mb-4">
                    <label for="car_controller">{{ __('ผู้ควบคุมรถ') }}</label>
                    <select id="car_controller" class="form-control @error('car_controller') is-invalid @enderror"
                        name="car_controller" required>
                        <option value="" disabled selected>{{ __('เลือกผู้ควบคุมรถ') }}</option>
                    </select>
                    @error('car_controller')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>



                <!-- วัตถุประสงค์ -->
                <div class="form-group mb-4">
                    <label for="objective">{{ __('วัตถุประสงค์') }}</label>
                    <input type="text" class="form-control @error('objective') is-invalid @enderror" id="objective"
                        name="objective" value="{{ old('objective') }}" required>
                    @error('objective')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- ประเภทของงาน (Moved below Objective) -->
                <div class="form-group mb-4">
                    <label for="work_id">{{ __('ประเภทของงาน') }}</label>
                    <select id="work_id" class="form-control @error('work_id') is-invalid @enderror" name="work_id"
                        required>
                        <option value="" disabled selected>{{ __('เลือกประเภทของงาน') }}</option>
                        @foreach($work_type as $work_tp)
                            <option value="{{ $work_tp->work_id }}" {{ old('work_id') == $work_tp->work_id ? 'selected' : '' }}>{{ $work_tp->work_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('work_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <!-- ประเภทรถยนต์ -->
                <div class="form-group mb-4">
                    <div class="col-md-6">
                        <label for="car_type">{{ __('ประเภทของรถยนต์') }}</label>
                        <div class="input-group">
                            <select id="car_type" class="form-control @error('car_type') is-invalid @enderror"
                                name="car_type" {{ old('car_rent') ? 'disabled' : 'required' }}>
                                <option value="" disabled selected>{{ __('เลือกประเภทของรถยนต์') }}</option>
                                @php
                                    $hasDisplayedPickupTruck = false;
                                    $hasDisplayedVan = false;
                                @endphp
                                @foreach($vehicles as $vehicle)
                                                            @if(in_array($vehicle->icon_id, [1, 2, 3]) && !$hasDisplayedPickupTruck)
                                                                                        <option value="รถกระบะ" {{ old('car_type') == 'รถกระบะ' ? 'selected' : '' }}>
                                                                                            {{ __('รถกระบะ') }}
                                                                                        </option>
                                                                                        @php
                                                                                            $hasDisplayedPickupTruck = true; // แสดง "รถกระบะ" เพียงครั้งเดียว
                                                                                        @endphp
                                                            @elseif(in_array($vehicle->icon_id, [4, 5, 6]) && !$hasDisplayedVan)
                                                                                        <option value="รถตู้" {{ old('car_type') == 'รถตู้' ? 'selected' : '' }}>
                                                                                            {{ __('รถตู้') }}
                                                                                        </option>
                                                                                        @php
                                                                                            $hasDisplayedVan = true; // แสดง "รถตู้" เพียงครั้งเดียว
                                                                                        @endphp
                                                            @elseif(in_array($vehicle->icon_id, [7, 8, 9]))
                                                                <option value="เรือ" {{ old('car_type') == 'เรือ' ? 'selected' : '' }}>
                                                                    {{ __('เรือ') }}
                                                                </option>
                                                            @endif
                                @endforeach
                            </select>
                            <div class="input-group-append" style="margin-left: 20px;">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="car_rent" name="car_rent"
                                        onclick="toggleCarType()">
                                    <label class="form-check-label" for="car_rent">{{ __('รถเช่า') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @error('car_type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <script>
                    function toggleCarType() {
                        const carTypeSelect = document.getElementById('car_type');
                        const carRentCheckbox = document.getElementById('car_rent');
                        if (carRentCheckbox.checked) {
                            carTypeSelect.value = "รถเช่า"; // ตั้งค่า car_type เป็น "รถเช่า"
                            carTypeSelect.disabled = true; // ปิดฟิลเลือกประเภทของรถยนต์
                            carTypeSelect.required = false; // ไม่บังคับเลือก car_type
                        } else {
                            carTypeSelect.disabled = false; // เปิดฟิลเลือกประเภทของรถยนต์
                            carTypeSelect.required = true; // ทำให้ car_type กลับมาบังคับเลือก
                            carTypeSelect.value = ""; // รีเซ็ตค่า car_type
                        }
                    }
                </script>

                <!-- ให้รถไปรับที่ -->
                <div class="form-group mb-4">
                    <label for="car_pickup">{{ __('ให้รถไปรับที่ไหน') }}</label>
                    <input type="text" class="form-control @error('car_pickup') is-invalid @enderror" id="car_pickup"
                        name="car_pickup" value="{{ old('car_pickup') }}" required>
                    @error('car_pickup')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- วันที่ไป และ วันที่กลับ (In the same row) -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_date">{{ __('วันที่ไป') }}</label>
                            <input type="date"
                                class="form-control text-center datepicker   @error('start_date') is-invalid @enderror"
                                id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                            @error('start_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date">{{ __('วันที่กลับ') }}</label>
                            <input type="date"
                                class="form-control text-center datepicker @error('end_date') is-invalid @enderror"
                                id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                            @error('end_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>


                <!-- เวลาไป และ เวลากลับ -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_time">{{ __('เวลาไป') }}</label>
                            <input type="time"
                                class="form-control text-center @error('start_time') is-invalid @enderror"
                                id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                            @error('start_time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_time">{{ __('เวลากลับ') }}</label>
                            <input type="time" class="form-control text-center @error('end_time') is-invalid @enderror"
                                id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                            @error('end_time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- สถานที่ -->
                <div class="form-group mb-4">
                    <label for="location">{{ __('สถานที่ที่ไป') }}</label>
                    <input type="text" class="form-control @error('location') is-invalid @enderror" id="location"
                        name="location" value="{{ old('location') }}" required>
                    @error('location')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- จังหวัด, อำเภอ, ตำบล -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="provinces_id">{{ __('จังหวัด') }}</label>
                            <select id="provinces_id"
                                class="form-control text-center @error('provinces_id') is-invalid @enderror"
                                name="provinces_id" required>
                                <option value="" disabled selected>{{ __('เลือกจังหวัด') }}</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->provinces_id }}" {{ old('provinces_id') == $province->provinces_id ? 'selected' : '' }}>
                                        {{ $province->name_th }}
                                    </option>
                                @endforeach
                            </select>
                            @error('provinces_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="amphoe_id">{{ __('อำเภอ') }}</label>
                            <select id="amphoe_id"
                                class="form-control text-center @error('amphoe_id') is-invalid @enderror"
                                name="amphoe_id" required>
                                <option value="" disabled selected>{{ __('เลือกอำเภอ') }}</option>
                                <!-- Load amphoes dynamically based on province selection -->
                                @if(old('provinces_id'))
                                    @foreach($amphoe as $amph)
                                        <option value="{{ $amph->amphoe_id }}" {{ old('amphoe_id') == $amph->amphoe_id ? 'selected' : '' }}>
                                            {{ $amph->name_th }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('amphoe_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="district_id">{{ __('ตำบล') }}</label>
                            <select id="district_id"
                                class="form-control text-center @error('district_id') is-invalid @enderror"
                                name="district_id" required>
                                <option value="" disabled selected>{{ __('เลือกตำบล') }}</option>
                                <!-- Load districts dynamically based on amphoe selection -->
                                @if(old('amphoe_id'))
                                    @foreach($district as $dist)
                                        <option value="{{ $dist->district_id }}" {{ old('district_id') == $dist->district_id ? 'selected' : '' }}>
                                            {{ $dist->name_th }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('district_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- เอกสารที่แนบ -->
                <div class="form-group mb-4">
                    <label for="related_project">{{ __('เอกสารที่แนบ (PDF เท่านั้น)') }}</label>
                    <input type="file" class="form-control @error('related_project') is-invalid @enderror"
                        id="related_project" name="related_project" accept=".pdf">
                    @error('related_project')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <!-- ลายเซ็นของผู้ใช้ -->
                <div class="form-group mb-4">
                    <label for="signature">{{ __('ลงชื่อผู้ขอ') }}</label>
                    @if(Auth::user()->signature_name)
                        <div>
                            <img src="{{ asset('signatures/' . Auth::user()->signature_name) }}" alt="ลายเซ็นของผู้ใช้"
                                style="max-width: 530px; max-height: 120px;">
                        </div>
                    @else
                        <p>{{ __('ยังไม่มีการอัปโหลดลายเซ็น') }}</p>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">{{ __('ยืนยันแบบฟอร์ม') }}</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<!-- เพิ่ม CSS และ JS ของ select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


<script type="text/javascript">
    // เมื่อเปลี่ยนแปลงจังหวัด
    $('#provinces_id').on('change', function () {
        var provinceId = $(this).val();
        $('#amphoe_id').empty().append('<option value="" disabled selected>{{ __('เลือกอำเภอ') }}</option>');
        $('#district_id').empty().append('<option value="" disabled selected>{{ __('เลือกตำบล') }}</option>');

        if (provinceId) {
            $.ajax({
                url: '/get-amphoes/' + provinceId,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    console.log('Amphoes data:', data); // ตรวจสอบข้อมูลที่ได้รับ
                    $.each(data, function (key, value) {
                        $('#amphoe_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                },
                error: function (xhr) {
                    console.error('AJAX Error: ', xhr.responseText);
                }
            });
        }
    });

    // เมื่อเปลี่ยนแปลงอำเภอ
    $('#amphoe_id').on('change', function () {
        var amphoeId = $(this).val();
        $('#district_id').empty().append('<option value="" disabled selected>{{ __('เลือกตำบล') }}</option>');

        if (amphoeId) {
            $.ajax({
                url: '/get-districts/' + amphoeId,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    console.log('Districts data:', data); // ตรวจสอบข้อมูลที่ได้รับ
                    $.each(data, function (key, value) {
                        $('#district_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                },
                error: function (xhr) {
                    console.error('AJAX Error: ', xhr.responseText);
                }
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        // ฟังก์ชันเพื่อแปลงวันที่เป็นปีพุทธ
        function convertToBuddhistDate(date) {
            var buddhistYear = date.getFullYear() + 543;
            return buddhistYear + '-' +
                String(date.getMonth() + 1).padStart(2, '0') + '-' +
                String(date.getDate()).padStart(2, '0');
        }

        // ตั้งค่าวันที่ปัจจุบันเป็นวันที่จอง
        var todayUTC = new Date();
        var todayThailand = new Date(todayUTC.getTime() + (7 * 60 * 60 * 1000));
        document.getElementById('reservation_date').value = convertToBuddhistDate(todayThailand);

        // ตรวจสอบเมื่อเลือกวันที่ไป
        document.getElementById('start_date').addEventListener('change', function () {
            const startDate = this.value;
            document.getElementById('end_date').setAttribute('min', startDate); // วันที่กลับต้องไม่ต่ำกว่าวันที่ไป
        });
        // ตรวจสอบเมื่อเลือกวันที่กลับ
        document.getElementById('end_date').addEventListener('change', function () {
            const startDate = new Date(document.getElementById('start_date').value);
            const endDate = new Date(this.value);
            if (endDate < startDate) {
                alert('วันที่กลับต้องมากกว่าหรือเท่ากับวันที่ไป');
                this.value = ''; // เคลียร์ค่าถ้าผู้ใช้เลือกวันผิด
            }
        });

        // ฟังก์ชันเพื่อให้วันที่ไปและวันที่กลับไม่สามารถเลือกวันย้อนหลัง
        function setMinDates() {
            const today = new Date();
            const minDate = today.toISOString().split('T')[0]; // แปลงเป็นรูปแบบ YYYY-MM-DD
            document.getElementById('start_date').setAttribute('min', minDate);
            document.getElementById('end_date').setAttribute('min', minDate);
        }
        setMinDates(); // เรียกฟังก์ชันเมื่อโหลดหน้า
    });

    $(document).ready(function () {
        // เรียกใช้ Select2 ใน dropdown
        $('#employee_select').select2({
            placeholder: "{{ __('เลือกผู้ร่วมเดินทาง') }}", // แสดงข้อความเมื่อไม่มีการเลือก
            allowClear: true // ให้สามารถลบการเลือกได้
        });

        // เก็บค่าชื่อผู้ใช้ที่ล็อกอินอยู่
        var loggedInUserId = "{{ Auth::user()->id }}"; // รับ ID ของผู้ใช้ที่ล็อกอินอยู่
        var loggedInUserName = "{{ Auth::user()->name }} {{ Auth::user()->lname }} (ผู้ขอ)"; // ชื่อผู้ใช้ที่ล็อกอินอยู่

        // เพิ่มชื่อผู้ใช้ที่ล็อกอินใน dropdown เริ่มต้น
        var carController = $('#car_controller');
        carController.append('<option value="' + loggedInUserId + '">' + loggedInUserName + '</option>');

        // เก็บรายชื่อผู้ร่วมเดินทางในรูปแบบ JSON object
        let companions = {};

        // ฟังก์ชันเพื่ออัปเดตจำนวนผู้ร่วมเดินทาง
        function updateCompanionCount() {
            var companionItems = document.querySelectorAll('.companion-item');
            document.getElementById('sum_companion').value = companionItems.length || 0;

            // แปลง JSON object ของ companions ให้เป็นสตริงที่แยกด้วยเครื่องหมายจุลภาค
            let companionsArray = Object.keys(companions); // ใช้ key ของ object ซึ่งคือ ID
            let companionString = companionsArray.join(','); // ใช้เครื่องหมายจุลภาคแยก ID

            // อัปเดตค่าที่ซ่อนเพื่อเก็บข้อมูลแบบไม่มีวงเล็บหรือเครื่องหมายจุลภาค
            document.getElementById('companions_hidden').value = companionString;
        }

        // ฟังก์ชันเพื่อเพิ่มชื่อกลับไปใน dropdown
        function addNameBackToDropdown(name, id) {
            var dropdown = $('#employee_select');
            var option = new Option(name, id, false, false);
            dropdown.append(option).trigger('change'); // ใช้ trigger เพื่ออัปเดต Select2
        }

        // ฟังก์ชันอัปเดตรายชื่อผู้ควบคุมรถแบบเรียลไทม์
        function updateCarControllerDropdown() {
            var companionNames = Object.values(companions);
            carController.find('option:not(:first)').remove();
            carController.append('<option value="' + loggedInUserId + '">' + loggedInUserName + '</option>');
            companionNames.forEach(function (name) {
                var id = Object.keys(companions).find(key => companions[key] === name);
                carController.append('<option value="' + id + '">' + name + '</option>'); // ใช้ ID แทนชื่อ
            });
        }

        // เพิ่มชื่อพนักงานที่เลือกจาก dropdown ไปยังฟิลด์ผู้ร่วมเดินทาง
        $('#employee_select').on('change', function () {
            var selectedId = this.value; // ใช้ ID ของพนักงาน
            var selectedName = this.options[this.selectedIndex].text; // ใช้ชื่อเต็ม
            var companionName = document.getElementById('companion_name');

            if (selectedId) {
                companions[selectedId] = selectedName; // เก็บชื่อโดยใช้ ID เป็น key
                var newCompanion = document.createElement('span');
                newCompanion.classList.add('companion-item');
                newCompanion.style.display = 'flex';
                newCompanion.style.justifyContent = 'space-between';
                newCompanion.style.alignItems = 'center';
                newCompanion.style.margin = '5px 0';
                newCompanion.style.padding = '5px 10px';
                newCompanion.style.border = '1px solid #ccc';
                newCompanion.style.borderRadius = '4px';
                newCompanion.textContent = selectedName; // ใช้ชื่อเต็มในบรรทัดเดียวกัน

                // สร้างไอคอนลบ
                var removeIcon = document.createElement('i');
                removeIcon.classList.add('fas', 'fa-times');
                removeIcon.style.color = 'red';
                removeIcon.style.cursor = 'pointer';

                // ฟังก์ชันเมื่อคลิกไอคอนลบ
                removeIcon.addEventListener('click', function () {
                    addNameBackToDropdown(selectedName, selectedId);
                    newCompanion.remove(); // ลบองค์ประกอบของผู้ร่วมเดินทาง
                    delete companions[selectedId]; // ลบชื่อออกจาก object
                    updateCompanionCount();
                    updateCarControllerDropdown();
                });

                newCompanion.appendChild(removeIcon);
                companionName.appendChild(newCompanion);
                updateCompanionCount();
                updateCarControllerDropdown();

                // ลบตัวเลือกที่เลือกแล้วออกจาก dropdown
                var selectedOption = this.options[this.selectedIndex];
                selectedOption.parentNode.removeChild(selectedOption);
            }

            // ล้าง dropdown หลังจากเลือก
            this.value = '';
        });
    });

</script>

@endsection