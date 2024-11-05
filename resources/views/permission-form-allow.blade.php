@extends('layouts.app')

@section('content')
<div class="container mt-5">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($documents->isEmpty())
        <div class="alert alert-info">
            {{ __('ไม่มีเอกสารที่บันทึก') }}
        </div>
    @else
    @foreach($documents as $document)
    <!-- กรณีที่เอกสารถูกยกเลิก -->
    @if ($document->cancel_allowed != 'pending')
        <div class="card-body mb-4">
            <div class="d-flex align-items-center justify-content-center"
                style="border: 1px solid #dc3545; color: #dc3545; padding: 10px 20px; border-radius: 5px; text-align: center;">
                <div>
                    รายการคำขอถูกยกเลิกแล้วเนื่องจาก
                    <span style="display: inline-block; border-bottom: 1px solid #dc3545; padding-bottom: 0;">
                        " {{ $document->cancel_reason }} "
                    </span>
                </div>
            </div>
        </div>
    @endif


    <!-- ส่วนแสดงรายละเอียดเอกสาร -->
    <div class="card mb-4 shadow-sm border-1">
        <div @if ($document->cancel_allowed != 'pending') class="card-header bg-secondary text-white" @else
        class="card-header bg-primary text-white" @endif>
            <h5 class="mb-0">{{ __('เอกสาร ที่ : ') . $document->document_id }}</h5>
            <p class="mb-0">
                {{ __('วันที่ทำเรื่อง: ')
            . \Carbon\Carbon::parse($document->reservation_date)->format('d') . ' ' .
            \Carbon\Carbon::parse($document->reservation_date)->locale('th')->translatedFormat('F') . ' พ.ศ. ' .
            \Carbon\Carbon::parse($document->reservation_date)->format('Y')
            ?? 'N/A' }}
            </p>
            <p class="mb-0">
                {{ __('ประเภทงาน: ') . ($document->workType->work_name ?? 'N/A') }} <!-- เพิ่มการแสดงประเภทงาน -->
            </p>
        </div>
        <div class="card-body">
            <div class="card-body border p-3">
                <!-- ข้อมูลผู้ขอ -->
                <div class="mb-3">
                    <h6 class="text-muted">{{ __('ข้อมูลผู้ขอ') }}</h6>
                    <table class="table table-borderless border p-3">
                        <tr>
                            <td>
                                <strong>{{ __('ชื่อผู้ขอ') }}:</strong>
                                {{ optional($document->reqDocumentUsers->first())->name ?? 'N/A' }}
                                {{ optional($document->reqDocumentUsers->first())->lname ?? 'N/A' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>{{ __('ส่วนงาน') }}:</strong>
                                {{ optional($document->reqDocumentUsers->first()->division)->division_name ?? 'N/A' }}
                            </td>
                            <td>
                                <strong>{{ __('ฝ่ายงาน') }}:</strong>
                                {{ optional($document->reqDocumentUsers->first()->department)->department_name ?? 'N/A' }}
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- ข้อมูลการเดินทาง -->
                <div class="mb-3 border p-3">
                    <h6 class="text-muted">{{ __('ข้อมูลการเดินทาง') }}</h6>
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>{{ __('วัตถุประสงค์') }}:</strong> {{ $document->objective ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>{{ __('ผู้ร่วมเดินทาง') }}:</strong>
                                <ul class="list-unstyled">
                                    @php
                                        // ดึง ID ของผู้ร่วมเดินทาง
                                        $companionIds = explode(',', $document->companion_name);
                                        // ดึงข้อมูลผู้ใช้จากฐานข้อมูลตาม ID ที่ได้
                                        $companions = \App\Models\User::whereIn('id', $companionIds)->get();
                                        $visibleCount = 5; // จำนวนชื่อที่ต้องการแสดงเริ่มต้น
                                    @endphp

                                    @if($companions->isEmpty())
                                        <li>{{ __('ไม่มีผู้ร่วมเดินทาง') }}</li>
                                    @else
                                        @foreach($companions as $index => $companion)
                                            <li class="{{ $index >= $visibleCount ? 'd-none extra-companions' : '' }}">
                                                {{ $companion->name }} {{ $companion->lname }}
                                            </li>
                                        @endforeach

                                        @if($companions->count() > $visibleCount)
                                            <li>
                                                <a href="javascript:void(0);" id="toggle-button" onclick="toggleCompanions()">
                                                    {{ __('ดูเพิ่มเติม') }}
                                                </a>
                                            </li>
                                        @endif
                                    @endif
                                </ul>
                            </td>
                            <td>
                                <strong>{{ __('ผู้ร่วมเดินทางทั้งหมด') }}:</strong> {{ $document->sum_companion }}
                            </td>

                        </tr>
                        <tr>
                            <td><strong>{{ __('วันที่ไป') }}:</strong>
                                {{ optional($document->start_date)
            ? \Carbon\Carbon::parse($document->start_date)->format('d') . ' ' .
            \Carbon\Carbon::parse($document->start_date)->locale('th')->translatedFormat('F') . ' พ.ศ. ' .
            \Carbon\Carbon::parse($document->start_date)->format('Y') + 543
            : 'N/A' }}
                            </td>
                            <td><strong>{{ __('วันที่กลับ') }}:</strong>
                                {{ optional($document->end_date)
            ? \Carbon\Carbon::parse($document->end_date)->format('d') . ' ' .
            \Carbon\Carbon::parse($document->end_date)->locale('th')->translatedFormat('F') . ' พ.ศ. ' .
            \Carbon\Carbon::parse($document->end_date)->format('Y') + 543
            : 'N/A' }}
                            </td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('เวลาไป') }}:</strong>
                                {{ $document->start_time
            ? \Carbon\Carbon::parse($document->start_time)->format('H:i') . ' น.'
            : 'N/A' 
                                }}
                            </td>
                            <td><strong>{{ __('เวลากลับ') }}:</strong>
                                {{ $document->start_time
            ? \Carbon\Carbon::parse($document->end_time)->format('H:i') . ' น.'
            : 'N/A' 
                                }}
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- ข้อมูลสถานที่ -->
                <div class="mb-3 border p-3">
                    <h6 class="text-muted">{{ __('ข้อมูลสถานที่') }}</h6>
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>{{ __('สถานที่') }}:</strong> {{ $document->location ?? 'N/A' }}</td>
                            <td><strong>{{ __('ให้รถไปรับที่') }}:</strong> {{ $document->car_pickup ?? 'N/A' }}</td>
                            <td><strong>{{ __('รถประเภท') }}:</strong> {{ $document->car_type ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('จังหวัด') }}:</strong>
                                {{ optional($document->province)->name_th ?? 'N/A' }}</td>
                            <td><strong>{{ __('อำเภอ') }}:</strong> {{ optional($document->amphoe)->name_th ?? 'N/A' }}
                            </td>
                            <td><strong>{{ __('ตำบล') }}:</strong> {{ optional($document->district)->name_th ?? 'N/A' }}
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- โครงการที่เกี่ยวข้อง -->
                <div class="mt-4 border p-3">
                    <h6 class="text-muted">{{ __('โครงการที่เกี่ยวข้อง') }}</h6>
                    <p class="form-control-static">
                        @if($document->related_project)
                            <a href="{{ asset('storage/' . $document->related_project) }}" target="_blank"                                class="btn btn-outline-primary">{{ __('ดูไฟล์') }}</a>
                        @else
                            {{ __('ไม่มีไฟล์') }}
                        @endif
                    </p>
                </div>

                <!-- ลงชื่อผู้ขอ -->
                <div class="mt-4" style="text-align: right; margin-right: 50px;">
                    <strong>{{ __('ลงชื่อผู้ขอ:') }}</strong>
                    @if ($document->reqDocumentUsers->first()->signature_name)
                        <img src="{{ url('/signatures/' . basename($document->reqDocumentUsers->first()->signature_name)) }}"
                            alt="Signature Image" class="img-fluid mt-2" width="350" height="auto">
                    @endif
                </div>
            </div>

            <!-- เปรียบเป็นใบแจ้งงานคนขับรถ -->
            @if (in_array(auth()->user()->role_id, [2, 3, 11, 12]))
                @if ($document->allow_opcar == 'approved')


                    <div class="card mt-3 mb-4 shadow-sm border-1" style="">
                        <div class="card-header">
                            <h6 class="mb-0">{{ __('รถยนต์ปฏิบัติงาน') }}</h6>
                        </div>
                        <div class="card-body">
                            <label>{{ __('คนขับรถ :') }}</label>
                            {{ $document->carmanUser->name ?? 'N/A' }} {{ $document->carmanUser->lname ?? 'N/A' }}<br>
                            <label>{{ __('หมายเลขทะเบียนรถ :') }}</label>
                            {{ $document->vehicle->car_category ?? 'N/A'}} {{ $document->vehicle->car_regnumber ?? 'N/A'}}
                            {{ $document->vehicle->car_province ?? 'N/A'}}
                        </div>
                    </div>
                @endif
            @endif

        </div>
    </div>



    <!-- ส่วนการอนุมัติของผู้ที่เกี่ยวข้อง -->
    @if ($document->cancel_allowed != 'pending')
        <!-- ไม่ต้องแสดงการอนุณาติเพราะยกเลิกก่อนการaction -->
    @else
    <!-- หัวหน้างาน division -->
    @if (in_array(auth()->user()->role_id, [2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 11, 13, 14, 15, 16]))
    <form action="{{ route('documents.updateStatus') }}" method="POST" class="mb-4">
        @csrf
        <input type="hidden" name="document_id" value="{{ $document->document_id }}">
        @if (in_array(auth()->user()->role_id, [4, 5, 6, 7, 8, 9, 10]))
            @if ($document->allow_division == 'pending')
                <div class="card mb-4 shadow-sm border-1">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">{{ __('ความคิดเห็นหัวหน้าฝ่าย:') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <!-- ซ้าย: ฟอร์มเลือกสถานะของฝ่าย -->
                            <div>
                                <div class="d-flex mb-3 mt-3">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="radio" name="statusdivision" value="approved"
                                            id="approve_division" onchange="toggleDivisionReasonField(false)">
                                        <label class="form-check-label" for="approve_division">{{ __('อนุญาต') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="statusdivision" value="rejected"
                                            id="reject_division" onchange="toggleDivisionReasonField(true)">
                                        <label class="form-check-label" for="reject_division">{{ __('ไม่อนุญาต') }}</label>
                                    </div>
                                </div>
                                <div id="reason_field_division" style="display: none;">
                                    <label for="notallowed_reason_division">{{ __('เหตุผลที่ไม่อนุญาต:') }}</label>
                                    <input type="text" id="notallowed_reason_division" name="notallowed_reason_division"
                                        placeholder="{{ __('กรุณาระบุเหตุผล') }}"
                                        value="{{ old('notallowed_reason_division', $document->notallowed_reason) }}">
                                </div>
                            </div>
                            <!-- ขวา: ลายเซ็นของผู้ใช้ -->
                            <div style="text-align: right; margin-right: 50px;">
                                <strong>{{ __('ลงชื่อผู้ใช้:') }}</strong>
                                @if (Auth::user()->signature_name)
                                    <img src="{{ url('/signatures/' . basename(Auth::user()->signature_name)) }}"
                                        alt="Signature Image" class="img-fluid" width="250" height="auto">
                                @else
                                    <p class="text-danger">{{ __('กรุณาเพิ่มลายเซ็นที่หน้าแก้ไขโปรไฟล์') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">{{ __('บันทึก') }}</button>
                    </div>
                </div>
            @endif

        @elseif (in_array(auth()->user()->role_id, [13, 14, 15, 16]))
            @if ($document->allow_department == 'pending')
                <div class="card mb-4 shadow-sm border-1">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">{{ __('ความคิดเห็นหัวหน้างานวิจัย') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <!-- ซ้าย: ฟอร์มเลือกสถานะของหัวหน้างานวิจัย -->
                            <div>
                                <div class="d-flex mb-3 mt-3">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="radio" name="statusdepartment" value="approved"
                                            id="approve_department" onchange="toggleDepartmentReasonField(false)">
                                        <label class="form-check-label" for="approve_department">{{ __('อนุญาต') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="statusdepartment" value="rejected"
                                            id="reject_department" onchange="toggleDepartmentReasonField(true)">
                                        <label class="form-check-label" for="reject_department">{{ __('ไม่อนุญาต') }}</label>
                                    </div>
                                </div>
                                <div id="reason_field_department" style="display: none;">
                                    <label for="notallowed_reason_department">{{ __('เหตุผลที่ไม่อนุญาต:') }}</label>
                                    <input type="text" id="notallowed_reason_department" name="notallowed_reason_department"
                                        placeholder="{{ __('กรุณาระบุเหตุผล') }}"
                                        value="{{ old('notallowed_reason_department', $document->notallowed_reason) }}">
                                </div>
                            </div>
                            <div style="text-align: right; margin-right: 50px;">
                                <strong>{{ __('ลงชื่อผู้ใช้:') }}</strong>
                                @if (Auth::user()->signature_name)
                                    <img src="{{ url('/signatures/' . basename(Auth::user()->signature_name)) }}"
                                        alt="Signature Image" class="img-fluid" width="250" height="auto">
                                @else
                                    <p class="text-danger">{{ __('กรุณาเพิ่มลายเซ็นที่หน้าแก้ไขโปรไฟล์') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">{{ __('บันทึก') }}</button>
                    </div>
                </div>
            @endif

        @elseif (in_array(auth()->user()->role_id, [12]))
            @if ($document->allow_opcar == 'pending')
                <div class="card mb-4 shadow-sm border-1">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">{{ __('ความคิดเห็นคนสั่งรถ:') }}</h6>
                    </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="d-flex mb-3 mt-3">
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="radio" name="statusopcar" value="approved"
                                        id="approve_opcar" onchange="toggleReasonField(false); toggleVehicleAndDriver(true)">
                                    <label class="form-check-label" for="approve_opcar">{{ __('อนุญาต') }}</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="statusopcar" value="rejected"
                                        id="reject_opcar" onchange="toggleReasonField(true); toggleVehicleAndDriver(false)">
                                    <label class="form-check-label" for="reject_opcar">{{ __('ไม่อนุญาต') }}</label>
                                </div>
                            </div>

                            <div id="reason_field_opcar" style="display: none;">
                                <label for="notallowed_reason">{{ __('เหตุผลที่ไม่อนุญาต:') }}</label>
                                <input type="text" id="notallowed_reason" name="notallowed_reason"
                                    placeholder="{{ __('กรุณาระบุเหตุผล') }}"
                                    value="{{ old('notallowed_reason', $document->notallowed_reason) }}">
                            </div>

                            <div id="vehicle_driver_section" style="display: none;">
                                <select id="vehicle" class="form-control @error('car_id') is-invalid @enderror mt-3" name="car_id"
                                    required>
                                    <option value="" disabled selected>{{ __('เลือกยานพาหนะ') }}</option>
                                    @foreach($vehicles as $vehicle)
                                        @if ($vehicle->car_status == 'Y')
                                            <option value="{{ $vehicle->car_id }}" {{ old('car_id') == $vehicle->car_id ? 'selected' : '' }}>
                                                {{ $vehicle->car_category }} {{ $vehicle->car_regnumber }} {{ $vehicle->car_province }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('car_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <select id="users" class="form-control @error('carman') is-invalid @enderror mt-2" name="carman" required>
                                    <option value="" disabled selected>{{ __('เลือกคนขับรถ') }}</option>
                                        @foreach($users as $user)
                                            @if ($user->role_id == 11)
                                                <option value="{{ $user->id }}" {{ old('carman') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }} {{ $user->lname }}
                                                </option>
                                            @endif
                                        @endforeach
                                </select>
                                @error('carman')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                        </div>
                        <div style="text-align: right; margin-right: 50px;">
                            <strong>{{ __('ลงชื่อผู้ใช้:') }}</strong>
                            @if (Auth::user()->signature_name)
                                <img src="{{ url('/signatures/' . basename(Auth::user()->signature_name)) }}"
                                    alt="Signature Image" class="img-fluid" width="250" height="auto">
                            @else
                                <p class="text-danger">{{ __('กรุณาเพิ่มลายเซ็นที่หน้าแก้ไขโปรไฟล์') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary mt-2">{{ __('บันทึก') }}</button>
                </div>
            @endif
            <script>
                $(document).ready(function () {
                    $('#start_date, #end_date').on('change', function () {
                        let startDate = $('#start_date').val();
                        let endDate = $('#end_date').val();

                        if (startDate && endDate) {
                            $.ajax({
                                url: '/check-booking-availability', // API route
                                type: 'GET',
                                data: { start_date: startDate, end_date: endDate },
                                success: function (response) {
                                    if (response.exists) {
                                        alert('ช่วงเวลานี้ถูกจองแล้ว');
                                    }
                                }
                            });
                        }
                    });
                });
            </script>

        @elseif (in_array(auth()->user()->role_id, [2]))
            @if ($document->allow_officer == 'pending')
                <div class="card mb-4 shadow-sm border-1">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">{{ __('ความคิดเห็นหัวหน้าสำนักงาน:') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <!-- ซ้าย: ฟอร์มเลือกสถานะ -->
                            <div>
                                <div class="d-flex mb-3 mt-3">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="radio" name="statusofficer" value="approved"
                                            id="approve_officer" onchange="toggleOfficerReasonField(false)">
                                        <label class="form-check-label" for="approve_officer">{{ __('อนุญาต') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="statusofficer" value="rejected"
                                            id="reject_officer" onchange="toggleOfficerReasonField(true)">
                                        <label class="form-check-label" for="reject_officer">{{ __('ไม่อนุญาต') }}</label>
                                    </div>
                                </div>

                                <div id="reason_field_officer" style="display: none;">
                                    <label for="notallowed_reason_officer">{{ __('เหตุผลที่ไม่อนุญาต:') }}</label>
                                    <input type="text" id="notallowed_reason_officer" name="notallowed_reason_officer"
                                        placeholder="{{ __('กรุณาระบุเหตุผล') }}"
                                        value="{{ old('notallowed_reason_officer', $document->notallowed_reason) }}">
                                </div>
                            </div>
                            <!-- ขวา: ลายเซ็นของผู้ใช้ -->
                            <div style="text-align: right; margin-right: 50px;">
                                <strong>{{ __('ลงชื่อผู้ใช้:') }}</strong>
                                @if (Auth::user()->signature_name)
                                    <img src="{{ url('/signatures/' . basename(Auth::user()->signature_name)) }}"
                                        alt="Signature Image" class="img-fluid " width="250" height="auto">
                                @else
                                    <p class="text-danger">{{ __('กรุณาเพิ่มลายเซ็นที่หน้าแก้ไขโปรไฟล์') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">{{ __('บันทึก') }}</button>
                    </div>
                </div>
            @endif

        @elseif (in_array(auth()->user()->role_id, [3]))
            @if ($document->allow_director == 'pending')
                <div class="card mb-4 shadow-sm border-1">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">{{ __('ความคิดเห็นผู้อำนวยการ:') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <!-- ซ้าย: ฟอร์มเลือกสถานะของผู้กำกับ -->
                            <div>
                                <div class="d-flex mb-3 mt-3">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="radio" name="statusdirector" value="approved"
                                            id="approve_director" onchange="toggleDirectorReasonField(false)">
                                        <label class="form-check-label" for="approve_director">{{ __('อนุญาต') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="statusdirector" value="rejected"
                                            id="reject_director" onchange="toggleDirectorReasonField(true)">
                                        <label class="form-check-label" for="reject_director">{{ __('ไม่อนุญาต') }}</label>
                                    </div>
                                </div>

                                <div id="reason_field_director" style="display: none;">
                                    <label for="notallowed_reason_director">{{ __('เหตุผลที่ไม่อนุญาต:') }}</label>
                                    <input type="text" id="notallowed_reason_director" name="notallowed_reason_director"
                                        placeholder="{{ __('กรุณาระบุเหตุผล') }}"
                                        value="{{ old('notallowed_reason_director', $document->notallowed_reason) }}">
                                </div>
                            </div>
                            <!-- ขวา: ลายเซ็นของผู้ใช้ -->
                            <div style="text-align: right; margin-right: 50px;">
                                <strong>{{ __('ลงชื่อผู้ใช้:') }}</strong>
                                @if (Auth::user()->signature_name)
                                    <img src="{{ url('/signatures/' . basename(Auth::user()->signature_name)) }}"
                                        alt="Signature Image" class="img-fluid" width="250" height="auto">
                                @else
                                    <p class="text-danger">{{ __('กรุณาเพิ่มลายเซ็นที่หน้าแก้ไขโปรไฟล์') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">{{ __('บันทึก') }}</button>
                    </div>
                </div>
            @endif
        @elseif (in_array(auth()->user()->role_id, [11]))
            @if ($document->allow_carman == 'pending')
                <div class="card mb-4 shadow-sm border-1">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">{{ __('คนขับรถรับทราบงาน:') }}</h6>
                    </div>
                    <div class="card-body">
    <div class="d-flex justify-content-between">
        <!-- ซ้าย: ฟอร์มเลือกสถานะของผู้รับงาน -->
        <div>
            <div class="d-flex mb-3 mt-3">
                <div class="form-check me-3">
                    <input class="form-check-input" type="radio" name="statuscarman" value="approved"
                        id="approve_carman" onchange="toggleCarmanReasonField(false)">
                    <label class="form-check-label" for="approve_carman">{{ __('รับทราบ') }}</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="statuscarman" value="rejected"
                        id="reject_carman" onchange="toggleCarmanReasonField(true)">
                    <label class="form-check-label" for="reject_carman">{{ __('ไม่สามารถรับงานได้') }}</label>
                </div>
            </div>

            <div id="reason_field_carman" style="display: none;">
                <label for="carman_reason">{{ __('เหตุผลที่ไม่อนุญาต:') }}</label>
                <input type="text" id="carman_reason" name="carman_reason"
                    placeholder="{{ __('กรุณาระบุเหตุผล') }}"
                    value="{{ old('carman_reason', $document->carman_reason) }}">
            </div>
        </div>

        <!-- ขวา: ลายเซ็นของผู้ใช้ -->
        <div style="text-align: right; margin-right: 50px;">
            <strong>{{ __('ลงชื่อผู้ใช้:') }}</strong>
            @if (Auth::user()->signature_name)
                <img src="{{ url('/signatures/' . basename(Auth::user()->signature_name)) }}"
                    alt="Signature Image" class="img-fluid" width="250" height="auto">
            @else
                <p class="text-danger">{{ __('กรุณาเพิ่มลายเซ็นที่หน้าแก้ไขโปรไฟล์') }}</p>
            @endif
        </div>
    </div>
</div>
<div class="card-footer text-right">
    <button type="submit" class="btn btn-primary">{{ __('บันทึก') }}</button>
</div>

                </div>
            @endif
        @endif
    </form>
    @endif
    @endif

    <script>
        function toggleDivisionReasonField(isRejected) {
            const reasonField = document.getElementById('reason_field_division');
            const notallowedReasonInput = document.getElementById('notallowed_reason_division');

            if (isRejected) {
                reasonField.style.display = 'block';
                notallowedReasonInput.setAttribute('required', 'required'); // ตั้งให้เป็น required
            } else {
                reasonField.style.display = 'none';
                notallowedReasonInput.removeAttribute('required'); // ไม่ต้องการ required
            }
        }

        function toggleDepartmentReasonField(isRejected) {
            const reasonField = document.getElementById('reason_field_department');
            const notallowedReasonInput = document.getElementById('notallowed_reason_department');

            if (isRejected) {
                reasonField.style.display = 'block';
                notallowedReasonInput.setAttribute('required', 'required'); // ตั้งให้เป็น required
            } else {
                reasonField.style.display = 'none';
                notallowedReasonInput.removeAttribute('required'); // ไม่ต้องการ required
            }
        }

        function toggleOfficerReasonField(isRejected) {
            const reasonField = document.getElementById('reason_field_officer');
            const notallowedReasonInput = document.getElementsByName('notallowed_reason_officer')[0];

            if (isRejected) {
                reasonField.style.display = 'block';
                notallowedReasonInput.setAttribute('required', 'required'); // ตั้งให้เป็น required
            } else {
                reasonField.style.display = 'none';
                notallowedReasonInput.removeAttribute('required'); // ไม่ต้องการ required
            }
        }

        function toggleCarmanReasonField(isRejected) {
            const reasonField = document.getElementById('reason_field_carman');
            const carmanReasonInput = document.getElementsByName('carman_reason')[0];

            if (isRejected) {
                reasonField.style.display = 'block';
                carmanReasonInput.setAttribute('required', 'required'); // ตั้งให้เป็น required
            } else {
                reasonField.style.display = 'none';
                carmanReasonInput.removeAttribute('required'); // ไม่ต้องการ required
            }
        }


        function toggleDirectorReasonField(isRejected) {
            const reasonField = document.getElementById('reason_field_director');
            const notallowedReasonInput = document.getElementsByName('notallowed_reason_director')[0];

            if (isRejected) {
                reasonField.style.display = 'block';
                notallowedReasonInput.setAttribute('required', 'required'); // ตั้งให้เป็น required
            } else {
                reasonField.style.display = 'none';
                notallowedReasonInput.removeAttribute('required'); // ไม่ต้องการ required
            }
        }

        function toggleReasonField(isRejected) {
            const reasonField = document.getElementById('reason_field_opcar');
            const notallowedReasonInput = document.getElementById('notallowed_reason');

            if (isRejected) {
                reasonField.style.display = 'block';
                notallowedReasonInput.setAttribute('required', 'required');
            } else {
                reasonField.style.display = 'none';
                notallowedReasonInput.removeAttribute('required'); // ลบ required เมื่อฟิลด์ถูกซ่อน
                notallowedReasonInput.value = ''; // ล้างค่าในฟิลด์เหตุผล
            }
        }

        function toggleVehicleAndDriver(isApproved) {
            const vehicleDriverSection = document.getElementById('vehicle_driver_section');
            const vehicleInput = document.getElementById('vehicle');
            const driverInput = document.getElementById('users');

            if (isApproved) {
                vehicleDriverSection.style.display = 'block';
                vehicleInput.setAttribute('required', 'required');
                driverInput.setAttribute('required', 'required');
            } else {
                vehicleDriverSection.style.display = 'none';
                vehicleInput.removeAttribute('required'); // ลบ required เมื่อฟิลด์ถูกซ่อน
                driverInput.removeAttribute('required'); // ลบ required เมื่อฟิลด์ถูกซ่อน
                vehicleInput.value = ''; // ล้างค่ารถที่เลือก
                driverInput.value = ''; // ล้างค่าคนขับที่เลือก
            }
        }


    </script>

    @endforeach
    @endif
</div>
@endsection