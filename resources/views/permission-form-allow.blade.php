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
            <!-- หัวหน้างาน division -->
            @if (in_array(auth()->user()->role_id, [2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14, 15, 16]))
                <form action="{{ route('documents.updateStatus') }}" method="POST" class="mb-4">
                    @csrf
                    <input type="hidden" name="document_id" value="{{ $document->document_id }}">

                    @if (in_array(auth()->user()->role_id, [4, 5, 6, 7, 8, 9, 10]))
                        @if ($document->allow_division == 'pending')
                            <label>ความคิดเห็นหัวหน้าฝ่าย:</label>
                            <input type="radio" name="statusdivision" value="approved" onchange="toggleDivisionReasonField(false)"> อนุญาต
                            <input type="radio" name="statusdivision" value="rejected" onchange="toggleDivisionReasonField(true)"> ไม่อนุญาต

                            <div id="reason_field_division" style="display: none;">
                                <label>เหตุผลที่ไม่อนุญาต:</label>
                                <input type="text" id="notallowed_reason_division" name="notallowed_reason_division" placeholder="กรุณาระบุเหตุผล" value="{{ old('notallowed_reason_division', $document->notallowed_reason) }}">
                            </div>
                            <button type="submit">บันทึก</button>
                        @endif

                    @elseif (in_array(auth()->user()->role_id, [13, 14, 15, 16]))
                        @if ($document->allow_department == 'pending')
                            <label>ความคิดเห็นหัวหน้างานวิจัย:</label>
                            <input type="radio" name="statusdepartment" value="approved" onchange="toggleDepartmentReasonField(false)"> อนุญาต
                            <input type="radio" name="statusdepartment" value="rejected" onchange="toggleDepartmentReasonField(true)"> ไม่อนุญาต

                            <div id="reason_field_department" style="display: none;">
                                <label>เหตุผลที่ไม่อนุญาต:</label>
                                <input type="text" id="notallowed_reason_department" name="notallowed_reason_department" placeholder="กรุณาระบุเหตุผล" value="{{ old('notallowed_reason_department', $document->notallowed_reason) }}">
                            </div>
                            <button type="submit">บันทึก</button>
                        @endif

                    @elseif (in_array(auth()->user()->role_id, [12]))
                        @if ($document->allow_opcar == 'pending')
                            <label>ความคิดเห็นคนสั่งรถ:</label>
                            <input type="radio" name="statusopcar" value="approved" onchange="toggleReasonField(false)"> อนุญาต
                            <input type="radio" name="statusopcar" value="rejected" onchange="toggleReasonField(true)"> ไม่อนุญาต

                            <div id="reason_field_opcar" style="display: none;">
                                <label>เหตุผลที่ไม่อนุญาต:</label>
                                <input type="text" id="notallowed_reason" name="notallowed_reason" placeholder="กรุณาระบุเหตุผล" value="{{ old('notallowed_reason', $document->notallowed_reason) }}">
                            </div>

                            <select id="vehicle" class="form-control @error('car_id') is-invalid @enderror" name="car_id" required>
                                <option value="" disabled selected>{{ __('เลือกยานพาหนะ') }}</option>
                                @foreach($vehicles as $vehicle)
                                    @if ($vehicle -> car_status == 'Y')
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

                            <select id="users" class="form-control @error('car_id') is-invalid @enderror mt-2" name="carman" required>
                                <option value="" disabled selected>{{ __('เลือกคนขับรถ') }}</option>
                                @foreach($users as $user)
                                    @if ($user -> role_id == 11)
                                        <option value="{{ $user->id }}" {{ old('id') == $user->id ? 'selected' : '' }}>
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

                            <input type="hidden" name="document_id" value="{{ $document->document_id }}">
                            <button type="submit" class="mt-2">บันทึก</button>
                        @endif

                    @elseif (in_array(auth()->user()->role_id, [2]))
                        @if ($document->allow_officer == 'pending')
                            <label>ความคิดเห็นหัวหน้าสำนักงาน:</label>
                            <input type="radio" name="statusofficer" value="approved" onchange="toggleOfficerReasonField(false)"> อนุญาต
                            <input type="radio" name="statusofficer" value="rejected" onchange="toggleOfficerReasonField(true)"> ไม่อนุญาต

                            <div id="reason_field_officer" style="display: none;">
                                <label>เหตุผลที่ไม่อนุญาต:</label>
                                <input type="text" name="notallowed_reason_officer" placeholder="กรุณาระบุเหตุผล" value="{{ old('notallowed_reason_officer', $document->notallowed_reason) }}">
                            </div>
                            <button type="submit">บันทึก</button>
                        @endif

                    @elseif (in_array(auth()->user()->role_id, [3]))
                        @if ($document->allow_director == 'pending')
                            <label>ความคิดเห็นผู้อำนวยการ:</label>
                            <input type="radio" name="statusdirector" value="approved" onchange="toggleDirectorReasonField(false)"> อนุญาต
                            <input type="radio" name="statusdirector" value="rejected" onchange="toggleDirectorReasonField(true)"> ไม่อนุญาต

                            <div id="reason_field_director" style="display: none;">
                                <label>เหตุผลที่ไม่อนุญาต:</label>
                                <input type="text" name="notallowed_reason_director" placeholder="กรุณาระบุเหตุผล" value="{{ old('notallowed_reason_director', $document->notallowed_reason) }}">
                            </div>
                            <button type="submit">บันทึก</button>
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
        notallowedReasonInput.setAttribute('required', 'required'); // ตั้งให้เป็น required
    } else {
        reasonField.style.display = 'none';
        notallowedReasonInput.removeAttribute('required'); // ไม่ต้องการ required
    }
}
</script>

                </form>
            @endif








            <div class="card mb-4 shadow-sm border-1">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ __('เอกสาร ที่ : ') . $document->document_id }}</h5>
                    <p class="mb-0">
                        {{ __('วันที่ทำเรื่อง: ') . \Carbon\Carbon::parse($document->reservation_date)->format('d-m-Y') }}
                    </p>
                    <p class="mb-0">
                        {{ __('ประเภทงาน: ') . ($document->workType->work_name ?? 'N/A') }} <!-- เพิ่มการแสดงประเภทงาน -->
                    </p>
                </div>
                <div class="card-body">

                    <!-- ข้อมูลผู้ขอ -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">{{ __('ข้อมูลผู้ขอ') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($document->reqDocumentUsers as $docUser)
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><strong>{{ __('ชื่อผู้ขอ') }}</strong></label>
                                        <p class="form-control-static">{{ $docUser->name ?? 'N/A' }} {{ $docUser->lname ?? 'N/A' }}
                                        </p>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><strong>{{ __('ลงชื่อผู้ขอ') }}</strong></label>
                                        <p class="form-control-static">{{ $docUser->signature_name ?? 'N/A' }}</p>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><strong>{{ __('ส่วนงาน') }}</strong></label>
                                        <p class="form-control-static">{{ $docUser->division->division_name ?? 'N/A' }}</p>
                                        <!-- เพิ่มการแสดงชื่อส่วนงาน -->
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><strong>{{ __('ฝ่ายงาน') }}</strong></label>
                                        <p class="form-control-static">{{ $docUser->department->department_name ?? 'N/A' }}</p>
                                        <!-- เพิ่มการแสดงชื่อฝ่ายงาน -->
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>


                    <!-- ข้อมูลการเดินทาง -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">{{ __('ข้อมูลการเดินทาง') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><strong>{{ __('ผู้ร่วมเดินทาง') }}</strong></label>
                                    <ul class="list-unstyled" style="max-height: 150px; overflow-y: auto;">
                                        @foreach(explode(',', $document->companion_name) as $name)
                                            <li>{{ trim($name) }}</li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><strong>{{ __('วัตถุประสงค์') }}</strong></label>
                                    <p class="form-control-static">{{ $document->objective }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><strong>{{ __('วันที่ไป') }}</strong></label>
                                    <p class="form-control-static">
                                        {{ \Carbon\Carbon::parse($document->start_date)->format('d-m-Y') }}
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><strong>{{ __('วันที่กลับ') }}</strong></label>
                                    <p class="form-control-static">
                                        {{ \Carbon\Carbon::parse($document->end_date)->format('d-m-Y') }}
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><strong>{{ __('เวลาไป') }}</strong></label>
                                    <p class="form-control-static">{{ $document->start_time }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><strong>{{ __('เวลากลับ') }}</strong></label>
                                    <p class="form-control-static">{{ $document->end_time }}</p>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label"><strong>{{ __('ผู้ร่วมเดินทางทั้งหมด') }}</strong></label>
                                    <p class="form-control-static">{{ $document->sum_companion }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ข้อมูลสถานที่ -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">{{ __('ข้อมูลสถานที่') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label"><strong>{{ __('สถานที่') }}</strong></label>
                                    <p class="form-control-static">{{ $document->location }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label"><strong>{{ __('จังหวัด') }}</strong></label>
                                    <p class="form-control-static">{{ $document->province->name_th ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label"><strong>{{ __('อำเภอ') }}</strong></label>
                                    <p class="form-control-static">{{ $document->amphoe->name_th ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label"><strong>{{ __('ตำบล') }}</strong></label>
                                    <p class="form-control-static">{{ $document->district->name_th ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label"><strong>{{ __('รถประเภท') }}</strong></label>
                                    <p class="form-control-static">{{ $document->car_type }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- โครงการที่เกี่ยวข้อง -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <label class="form-label"><strong>{{ __('โครงการที่เกี่ยวข้อง') }}</strong></label>
                            @if($document->related_project)
                                <p class="form-control-static">
                                    <a href="{{ Storage::url($document->related_project) }}" target="_blank"
                                        class="btn btn-outline-primary">{{ __('ดูไฟล์') }}</a>
                                </p>
                            @else
                                <p class="form-control-static">{{ __('ไม่มีไฟล์') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection