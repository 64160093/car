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
                                    <option value="{{ $users->name }} {{ $users->lname }}">
                                        {{ $users->name }} {{ $users->lname }}
                                    </option>
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
                            <option value="{{ $work_tp->work_id }}" {{ old('work_id') == $work_tp->work_id ? 'selected' : '' }}>
                                {{ $work_tp->work_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('work_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

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
                        <!-- <div class="mb-4">
                            <label for="booking-date" class="form-label">เลือกวันที่</label>
                            <input type="date" id="booking-date" class="form-control" 
                            onchange="updateBookings()" 
                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" 
                            max="{{ \Carbon\Carbon::now()->addDays(7)->format('Y-m-d') }}">
                         </div> -->
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

                <!-- ประเภทรถยนต์ -->
                <div class="form-group mb-4">
                    <label for="car_type">{{ __('ประเภทของรถยนต์') }}</label>
                    <select id="car_type" class="form-control @error('car_type') is-invalid @enderror" name="car_type"
                        required>
                        <option value="" disabled selected>{{ __('เลือกประเภทของรถยนต์') }}</option>
                        <option value="รถกระบะ">{{ __('รถกระบะ') }}</option>
                        <option value="รถตู้">{{ __('รถตู้') }}</option>
                        <option value="เรือ">{{ __('เรือ') }}</option>
                    </select>
                    @error('car_type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <!-- เอกสารที่แนบ -->
                <div class="form-group mb-4">
                    <label for="related_project">{{ __('เอกสารที่แนบ (PDF เท่านั้น)') }}</label>
                    <input type="file" class="form-control @error('related_project') is-invalid @enderror"
                        id="related_project" name="related_project">
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
        // เก็บค่าชื่อผู้ใช้ที่ล็อกอินอยู่
        var loggedInUser = "{{ Auth::user()->name }} {{ Auth::user()->lname }} (ผู้ขอ)";

        // เพิ่มชื่อผู้ใช้ที่ล็อกอินใน dropdown เริ่มต้น
        var carController = $('#car_controller');
        carController.append('<option value="' + loggedInUser + '">' + loggedInUser + '</option>');

        // เก็บรายชื่อผู้ร่วมเดินทาง
        let companions = [];

        // ฟังก์ชันเพื่ออัปเดตจำนวนผู้ร่วมเดินทาง
        function updateCompanionCount() {
            var companionItems = document.querySelectorAll('.companion-item');
            document.getElementById('sum_companion').value = companionItems.length || 0;
            // อัปเดตค่าที่ซ่อนเพื่อเก็บ JSON
            document.getElementById('companions_hidden').value = JSON.stringify(companions);
        }

        // ฟังก์ชันเพื่อเพิ่มชื่อกลับไปใน dropdown
        function addNameBackToDropdown(name) {
            var dropdown = document.getElementById('employee_select');
            var option = document.createElement('option');
            option.value = name;
            option.text = name;
            dropdown.add(option);
        }

        // ฟังก์ชันอัปเดตรายชื่อผู้ควบคุมรถแบบเรียลไทม์
        function updateCarControllerDropdown() {
            var companionNames = companions; // ใช้ companions แทน
            // ล้างตัวเลือกที่มีอยู่ทั้งหมด ยกเว้นชื่อผู้ขอ
            carController.find('option:not(:first)').remove();
            // เพิ่มชื่อผู้ขอกลับเข้าไปใหม่
            carController.append('<option value="' + loggedInUser + '">' + loggedInUser + '</option>');
            // เพิ่มรายชื่อผู้ร่วมเดินทางลงใน dropdown ผู้ควบคุมรถ
            companionNames.forEach(function (name) {
                carController.append('<option value="' + name + '">' + name + '</option>');
            });
        }

        // เพิ่มชื่อพนักงานที่เลือกจาก dropdown ไปยังฟิลด์ผู้ร่วมเดินทาง
        document.getElementById('employee_select').addEventListener('change', function () {
            var selectedName = this.value;
            var companionName = document.getElementById('companion_name');

            // ถ้ามีการเลือกพนักงาน ให้เพิ่มลงไปในฟิลด์ผู้ร่วมเดินทาง
            if (selectedName) {
                companions.push(selectedName); // เพิ่มชื่อในอาเรย์
                var newCompanion = document.createElement('span');
                newCompanion.classList.add('companion-item');
                newCompanion.style.display = 'flex'; // ใช้ flexbox เพื่อจัดตำแหน่ง
                newCompanion.style.justifyContent = 'space-between'; // ชื่ออยู่ซ้าย ไอคอนอยู่ขวา
                newCompanion.style.alignItems = 'center';
                newCompanion.style.margin = '5px 0'; // เพิ่มระยะห่างระหว่างบรรทัด
                newCompanion.style.padding = '5px 10px';
                newCompanion.style.border = '1px solid #ccc';
                newCompanion.style.borderRadius = '4px';
                newCompanion.textContent = selectedName;

                // สร้างไอคอนลบ
                var removeIcon = document.createElement('i');
                removeIcon.classList.add('fas', 'fa-times'); // ใช้ไอคอนกากบาทจาก Font Awesome
                removeIcon.style.color = 'red';
                removeIcon.style.cursor = 'pointer';

                // ฟังก์ชันเมื่อคลิกไอคอนลบ
                removeIcon.addEventListener('click', function () {
                    // นำชื่อกลับไปยัง dropdown
                    addNameBackToDropdown(selectedName);

                    // ลบชื่อพนักงานจากรายการ
                    companionName.removeChild(newCompanion);
                    companions = companions.filter(name => name !== selectedName); // ลบชื่อออกจากอาเรย์
                    updateCompanionCount(); // อัปเดตจำนวนผู้ร่วมเดินทาง
                    updateCarControllerDropdown(); // อัปเดตรายชื่อใน dropdown ผู้ควบคุมรถ
                });

                // เพิ่มไอคอนลบเข้าไปในชื่อพนักงาน
                newCompanion.appendChild(removeIcon);

                // เพิ่มชื่อพนักงานลงใน companion_name
                companionName.appendChild(newCompanion);
                updateCompanionCount(); // อัปเดตจำนวนผู้ร่วมเดินทาง
                updateCarControllerDropdown(); // อัปเดตรายชื่อใน dropdown ผู้ควบคุมรถ

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