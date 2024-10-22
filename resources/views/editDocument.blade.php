@extends('layouts.app')
<style>
    .companion-list-form-goup {
        max-height: 150px;
        max-width: 500px;
        overflow-y: auto;
        /* ทำให้สามารถ scroll ได้ */
        border: 1px solid #ced4da;
        /* ขอบของ container */
        border-radius: 0.25rem;
        /* มุมมน */
        padding: 0.5rem;
        /* การ padding */
        margin-top: 10px;
        /* ระยะห่างจากด้านบน */
    }

    .companion-list-form-goup ul {
        list-style-type: none;
        /* ไม่แสดง bullet points */
        padding: 0;
        /* ลบ padding */
        margin: 0;
        /* ลบ margin */
    }

    .form-group-item {
        display: flex;
        justify-content: space-between;
        /* จัดแนวให้เรียบง่าย */
        align-items: center;
        /* จัดแนวให้อยู่กลาง */
    }
</style>

</style>
@section('content')
<div class="container mt-5">
    <h1>{{ __('หน้าแก้ไขเอกสาร') }}</h1>

    @if(!$document)
        <div class="alert alert-info">
            {{ __('ไม่มีข้อมูลเอกสาร') }}
        </div>
    @else
        <form method="POST" action="{{ route('documents.update.edit', $document->document_id) }}">
            @csrf
            @method('PUT')
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ __('เอกสารที่: ') . $document->document_id }}</h5>
                    <p class="mb-0">
                        {{ __('วันที่ทำเรื่อง: ') . \Carbon\Carbon::parse($document->reservation_date)->format('d-m-Y') }}
                    </p>
                </div>

                <div class="card-body border p-3">
                    <!-- ข้อมูลผู้ขอ -->
                    <div class="mb-3">
                        <h6 class="text-muted">{{ __('ข้อมูลผู้ขอ') }}</h6>
                        <table class="table table-borderless border p-3">
                            <tr>
                                <td><strong>{{ __('ชื่อผู้ขอ') }}:</strong>
                                    {{ $document->reqDocumentUsers->first()->name ?? 'N/A' }}
                                    {{ $document->reqDocumentUsers->first()->lname ?? 'N/A' }}
                                </td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('ส่วนงาน') }}:</strong>
                                    {{ $document->reqDocumentUsers->first()->division->division_name ?? 'N/A' }}</td>
                                <td><strong>{{ __('ฝ่ายงาน') }}:</strong>
                                    {{ $document->reqDocumentUsers->first()->department->department_name ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- ข้อมูลการเดินทาง -->
                    <h6 class="text-muted">{{ __('ข้อมูลการเดินทาง') }}</h6>
                    <div class="mb-3 border p-3">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>{{ __('วัตถุประสงค์') }}:</strong>
                                    <input type="text" name="objective" class="form-control"
                                        value="{{ old('objective', $document->objective) }}">
                                </td>
                                <td>
                                    <strong>{{ __('ผู้ร่วมเดินทางทั้งหมด') }}:</strong>
                                    <span id="total-companions">{{ count(explode(',', $document->companion_name)) }}
                                        คน</span>

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div id="companion-list" class="mt-2">
                                        <strong>{{ __('ผู้ร่วมเดินทาง:') }}</strong>
                                        <div class="companion-list-form-goup">
                                            <ul>
                                                @if(!empty($companions))
                                                    @foreach ($companions as $companion)
                                                        <li class="form-group-item d-flex justify-content-between align-items-center"
                                                            data-id="{{ $companion->id }}">
                                                            {{ $companion->name }} {{ $companion->lname }}
                                                            <button type="button" class="btn btn-danger btn-sm remove-companion"
                                                                data-id="{{ $companion->id }}">{{ __('ลบ') }}</button>
                                                        </li>
                                                    @endforeach
                                                @else
                                                    <p>{{ __('ไม่มีผู้ร่วมเดินทางที่เลือก') }}</p>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </td>

                                <td><button type="button" class="btn btn-info" data-bs-toggle="modal"
                                        data-bs-target="#companionModal">
                                        {{ __('เพิ่มผู้ร่วมเดินทาง') }}
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('ผู้ควบคุมรถ') }}:</strong>
                                    {{ $document->carController->name ?? 'N/A' }}
                                    {{ $document->carController->lname ?? 'N/A' }}
                                </td>

                            </tr>
                            <tr>
                                <td><strong>{{ __('วันที่ไป') }}:</strong>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ old('start_date', $document->start_date) }}">
                                </td>
                                <td><strong>{{ __('วันที่กลับ') }}:</strong>
                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ old('end_date', $document->end_date) }}">
                                </td>

                            </tr>
                            <tr>
                                <td><strong>{{ __('เวลาไป') }}:</strong>
                                    <input type="time" name="start_time" class="form-control"
                                        value="{{ old('start_time', $document->start_time) }}">
                                </td>
                                <td><strong>{{ __('เวลากลับ') }}:</strong>
                                    <input type="time" name="end_time" class="form-control"
                                        value="{{ old('end_time', $document->end_time) }}">
                                </td>
                            </tr>
                        </table>
                    </div>

                    <h6 class="text-muted">{{ __('ข้อมูลสถานที่') }}</h6>
                    <div class="mb-3 border p-3">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>{{ __('สถานที่') }}:</strong>
                                    <input type="text" name="location" class="form-control"
                                        value="{{ old('location', $document->location) }}">
                                </td>
                                <td><strong>{{ __('ประเภทของรถ') }}:</strong>
                                    <span>{{ old('car_type', $document->car_type) ?? 'N/A' }}</span>
                                </td>
                            </tr>
                        </table>
                        <input type="hidden" name="car_type" value="{{ old('car_type', $document->car_type) }}">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="provinces_id">{{ __('จังหวัด') }}</label>
                                    <select id="provinces_id"
                                        class="form-control text-center @error('provinces_id') is-invalid @enderror"
                                        name="provinces_id" required>
                                        <option value="">{{ __('เลือกจังหวัด') }}</option>
                                        @foreach($provinces as $province)<option value="{{ $province->provinces_id }}" {{ old('provinces_id', $document->province->provinces_id ?? '') == $province->provinces_id ? 'selected' : '' }}>{{ $province->name_th }}
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
                                        <option value="">{{ __('เลือกอำเภอ') }}</option>
                                        @if($amphoe)
                                            @foreach($amphoe as $amphoes)
                                                <option value="{{ $amphoes->amphoe_id }}" {{ old('amphoe_id', $document->amphoe->amphoe_id ?? '') == $amphoes->amphoe_id ? 'selected' : '' }}>
                                                    {{ $amphoes->name_th }}
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
                                        <option value="">{{ __('เลือกตำบล') }}</option>
                                        @if($district)
                                            @foreach($district as $districts)<option value="{{ $districts->district_id }}" {{ old('district_id', $document->district->district_id ?? '') == $districts->district_id ? 'selected' : '' }}>{{ $districts->name_th }}
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
                    </div>

                    <!-- โครงการที่เกี่ยวข้อง -->
                    <h6 class="text-muted">{{ __('โครงการที่เกี่ยวข้อง') }}</h6>
                    <div class="mt-4 border p-3">
                        <p class="form-control-static">
                            @if($document->related_project)
                                <a href="{{ Storage::url($document->related_project) }}" target="_blank"
                                    class="btn btn-outline-primary">{{ __('ดูไฟล์') }}</a>
                            @else
                                {{ __('ไม่มีไฟล์') }}
                            @endif
                        </p>
                    </div>

                    <!-- Hidden input for companion_name -->
                    <input type="hidden" name="companion_name" value="{{ $document->companion_name }}">

                    <!-- ปุ่มบันทึก -->
                    <div class="mt-4" style="text-align: right;">
                        <button type="submit" class="btn btn-success">{{ __('บันทึกการแก้ไข') }}</button>
                    </div>
                </div>
            </div>
        </form>
    @endif
</div>

<!-- Modal เพิ่มผู้ร่วมเดินทาง -->
<style>
    .custom-modal {
        max-width: 600px;
        width: 100%;
        overflow-y: auto;
    }
</style>
<div class="modal fade" id="companionModal" tabindex="-1" aria-labelledby="companionModalLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="companionModalLabel">{{ __('เพิ่มผู้ร่วมเดินทาง') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="companion-list-container" style="max-height: 200px; overflow-y: auto;">
                    <select id="companions" name="companions[]" class="form-control" multiple>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ in_array($user->id, explode(',', $document->companion_name)) ? 'style=display:none;' : '' }}>
                                {{ $user->name }} {{ $user->lname }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('ปิด') }}</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
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
        const companionsSelect = document.getElementById('companions');
        const companionList = document.getElementById('companion-list').querySelector('ul'); // Select the <ul> directly
        const totalCompanions = document.getElementById('total-companions'); // Track total companions

        companionsSelect.addEventListener('change', function () {
            const selectedOptions = Array.from(companionsSelect.selectedOptions);
            selectedOptions.forEach(option => {
                const companionId = option.value;
                const companionName = option.textContent;

                // ตรวจสอบว่าผู้ร่วมเดินทางได้ถูกเพิ่มไปแล้วหรือยัง
                if (!Array.from(companionList.querySelectorAll('li')).some(li => li.dataset.id === companionId)) {
                    const li = document.createElement('li');
                    li.className = 'form-group-item d-flex justify-content-between align-items-center';
                    li.dataset.id = companionId;
                    li.innerHTML = `${companionName} <button type="button" class="btn btn-danger btn-sm remove-companion" data-id="${companionId}">{{ __('ลบ') }}</button>`;

                    // เพิ่มผู้ร่วมเดินทางใหม่ลงในลิสต์ที่มีอยู่
                    companionList.appendChild(li);

                    // อัปเดตจำนวนผู้ร่วมเดินทางทั้งหมดและค่าใน hidden input (companion_name)
                    updateTotalCompanions();
                    updateCompanionNames();

                    // ซ่อนตัวเลือกที่ถูกเลือกใน dropdown
                    option.style.display = 'none';
                }
            });
        });

        companionList.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-companion')) {
                const companionId = event.target.dataset.id;
                const liToRemove = companionList.querySelector(`li[data-id="${companionId}"]`);
                if (liToRemove) {
                    companionList.removeChild(liToRemove);

                    // แสดงตัวเลือกใน dropdown อีกครั้ง
                    const optionToShow = Array.from(companionsSelect.options).find(option => option.value === companionId);
                    if (optionToShow) {
                        optionToShow.style.display = 'block';
                    }

                    // อัปเดตจำนวนผู้ร่วมเดินทางทั้งหมดและค่าใน hidden input (companion_name)
                    updateTotalCompanions();
                    updateCompanionNames();
                }
            }
        });

        // ฟังก์ชันอัปเดตจำนวนผู้ร่วมเดินทางทั้งหมด
        function updateTotalCompanions() {
            const total = companionList.querySelectorAll('li').length;
            totalCompanions.textContent = `${total} คน`;
        }

        // ฟังก์ชันอัปเดตค่าใน hidden input (companion_name)
        function updateCompanionNames() {
            const companionListItems = companionList.querySelectorAll('li');
            const companionIds = Array.from(companionListItems).map(li => li.dataset.id);
            document.querySelector('input[name="companion_name"]').value = companionIds.join(',');
        }

        // เรียกใช้ฟังก์ชันนี้ทุกครั้งที่มีการเพิ่มหรือลบผู้ร่วมเดินทาง
    });
</script>


@endsection