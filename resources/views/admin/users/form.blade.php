@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container">
    @if (auth()->user()->is_admin == 1)
        <h2>รายการคำขอทั้งหมด</h2>
    @endif

    <!-- ช่องค้นหาข้อมูล -->
    <div class="container-fluid mt-2">
        <form method="GET" action="{{ route('admin.users.searchform') }}">
            <div class="d-flex align-items-center">
                <input type="search" id="searchName" name="q" class="form-control me-2" placeholder="ค้นหาข้อมูล"
                    aria-label="Search" value="{{ request()->get('q') }}">
                <button type="submit" class="btn btn-primary">ค้นหา</button>
                {{-- Dropdown สำหรับกรองสถานะ --}}
                <select name="filter" id="filter" class="form-select me-2 ms-2" style="max-width: 200px;"
                    onchange="this.form.submit()">
                    <option value="">ทั้งหมด</option>
                    <option value="completed" {{ request('filter') == 'completed' ? 'selected' : '' }}>สำเร็จแล้ว</option>
                    <option value="pending" {{ request('filter') == 'pending' ? 'selected' : '' }}>พิจารณา</option>
                    <option value="cancelled" {{ request('filter') == 'cancelled' ? 'selected' : '' }}>ไม่อนุมัติ/ยกเลิก
                    </option>
                </select>
            </div>
            <!-- ฟิลด์สำหรับกรองช่วงเดือน ปี และเวลา -->
            <div class="row mt-3 align-items-end">
                <div class="col-md-2">
                    <label for="start_date">เดือนและปีเริ่มต้น</label>
                    <input type="month" name="start_date" id="start_date" class="form-control"
                        value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="end_date">เดือนและปีสิ้นสุด</label>
                    <input type="month" name="end_date" id="end_date" class="form-control"
                        value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="start_time">เวลาเริ่มต้น</label>
                    <select name="start_time" id="start_time" class="form-control">
                        <option value="">เลือกเวลา</option>
                        @for ($i = 0; $i < 24; $i++)
                            <option value="{{ sprintf('%02d:00', $i) }}" {{ request('start_time') == sprintf('%02d:00', $i) ? 'selected' : '' }}>
                                {{ sprintf('%02d:00', $i) }} น.
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="end_time">เวลาสิ้นสุด</label>
                    <select name="end_time" id="end_time" class="form-control">
                        <option value="">เลือกเวลา</option>
                        @for ($i = 0; $i < 24; $i++)
                            <option value="{{ sprintf('%02d:00', $i) }}" {{ request('end_time') == sprintf('%02d:00', $i) ? 'selected' : '' }}>
                                {{ sprintf('%02d:00', $i) }} น.
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end"> <!-- ใช้ d-flex และ align-items-end -->
                    <button type="submit" class="btn btn-primary" style="font-size: 0.9em;">กรองตามช่วงเวลา</button>
                </div>
            </div>
        </form>
    </div>

    <!-- ตารางแสดงข้อมูลเอกสาร -->
    @if($documents->isEmpty())
        <div class="alert alert-info mt-4">
            {{ __('ไม่มีฟอร์มสำหรับการตรวจสอบ') }}
        </div>
    @else
        <div class="table-responsive mt-4 mb-4">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr class="text-center">
                        <th>#</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>วัตถุประสงค์</th>
                        <th>วันที่เดินทางไป</th>
                        <th>วันที่เดินทางกลับ</th>
                        <th>สถานะปัจจุบัน</th>
                        <th>ดูสถานะทั้งหมด</th>
                        <th>PDF คำร้อง/คนขับ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents->groupBy(function ($date) {
                            return \Carbon\Carbon::parse($date->created_at)->format('F Y');
                        }) as $month => $groupedDocuments)
                                    @foreach($groupedDocuments as $document)
                                            <tr class="text-center">
                                                @php
                                                    $requester = $document->reqDocumentUsers->first();
                                                @endphp
                                                <td>{{ $requester->id }}</td>
                                                <td>{{ $requester->name }} {{ $requester->lname }}</td>
                                                <td style="max-width: 160px; ">{{ $document->objective }}</td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($document->start_date)->format('d') }}
                                                    {{ \Carbon\Carbon::parse($document->start_date)->locale('th')->translatedFormat('F') }} พ.ศ.
                                                    {{ \Carbon\Carbon::parse($document->start_date)->format('Y') + 543 }}<br>
                                                    เวลา : {{ \Carbon\Carbon::parse($document->start_time)->format('H:i') }} น.
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($document->end_date)->format('d') }}
                                                    {{ \Carbon\Carbon::parse($document->end_date)->locale('th')->translatedFormat('F') }} พ.ศ.
                                                    {{ \Carbon\Carbon::parse($document->end_date)->format('Y') + 543 }}<br>

                                                    เวลา : {{ \Carbon\Carbon::parse($document->end_time)->format('H:i') }} น.
                                                </td>
                                                <td>
                                                    <!-- ไม่มีการขอยกเลิก -->
                                                    @if ($document->cancel_allowed == 'pending')
                                                        @foreach($document->reqDocumentUsers as $docUser)
                                                            @if ($docUser->division_id == 2)
                                                                @if ($document->allow_department == 'pending')
                                                                    <span class="badge bg-warning">รอหัวหน้างานพิจารณา</span>
                                                                @elseif ($document->allow_department == 'approved')
                                                                    @include('partials.allow_status', ['document' => $document])
                                                                @else
                                                                    <span class="badge bg-danger">หัวหน้างานไม่อนุมัติ</span>
                                                                    @if ($document->notallowed_reason)
                                                                        <br><span>เหตุผล: {{ $document->notallowed_reason }}</span>
                                                                    @endif
                                                                @endif
                                                            @else
                                                                @include('partials.allow_status', ['document' => $document])
                                                            @endif
                                                        @endforeach
                                                        <!-- ยกเลิกก่อนถึงผอ. -->
                                                    @elseif ($document->allow_director == 'pending' && $document->cancel_reason != null)
                                                        @if ($document->cancel_admin == 'Y')
                                                            <span class="badge bg-secondary">รายการคำขอถูกยกเลิกแล้ว</span>
                                                        @else
                                                            <span class="badge bg-info">รอแอดมินอนุมัติคำขอยกเลิก</span>
                                                        @endif
                                                        <!-- ผอ.อนุมัติไปแล้ว -->
                                                    @elseif ($document->allow_director != 'pending' && $document->cancel_reason != null)
                                                        @if ($document->cancel_admin != 'Y')
                                                            <span class="badge bg-info">รอแอดมินอนุมัติคำขอยกเลิก</span>
                                                        @elseif ($document->cancel_admin == 'Y' && $document->cancel_director != 'Y')
                                                            <span class="badge bg-info">รอผู้อำนวยการอนุมัติคำขอยกเลิก</span>
                                                        @elseif ($document->cancel_admin == 'Y' && $document->cancel_director == 'Y')
                                                            <span class="badge bg-secondary">รายการคำขอถูกยกเลิกแล้ว</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-secondary">รายการคำขอถูกยกเลิกแล้ว</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    <a href="{{ route('documents.status') }}?id={{ $document->document_id }}"
                                                        class="btn btn-outline-primary">สถานะ</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('PDF.document') }}?id={{ $document->document_id }}"
                                                        class="btn btn-outline-primary" target="_blank"> PDF
                                                    </a>
                                                    <!-- @if ($document->allow_director != 'pending')
                                                                        <a href="{{ route('PDF.document') }}?id={{ $document->document_id }}"
                                                                            class="btn btn-outline-primary"   target="_blank"> PDF
                                                                        </a>
                                                                    @else
                                                                        <button type="button" class="btn btn-secondary" disabled>PDF</button>
                                                                    @endif -->

                                                    @if ($document->allow_carman != 'pending')
                                                        @if ($document->reportFormance)
                                                            <a href="{{ route('report.showRepDoc.pdf') }}?id={{ $document->reportFormance->report_id }}"
                                                                class="btn btn-outline-primary" target="_blank"> PDF
                                                            </a>
                                                        @else
                                                            <button type="button" class="btn btn-secondary" disabled>PDF</button>
                                                        @endif
                                                    @else
                                                        <button type="button" class="btn btn-secondary" disabled>PDF</button>
                                                    @endif
                                                </td>
                                            </tr>
                                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    {{ $documents->appends(request()->query())->links() }}
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        // ฟังก์ชันสำหรับการปรับปรุงค่าเดือนสิ้นสุด
        function updateEndDateOptions() {
            const startDateValue = startDateInput.value;
            const currentEndDateValue = endDateInput.value; // เก็บค่าเดิมก่อน
            if (startDateValue) {
                const [year, month] = startDateValue.split('-').map(Number);
                const startMonth = month;
                const startYear = year;
                // กำหนดค่าที่เป็นไปได้สำหรับเดือนสิ้นสุด
                endDateInput.innerHTML = ''; // ล้างตัวเลือกเดิม
                // สร้างตัวเลือกเดือนสิ้นสุดที่เหมาะสม
                for (let i = 0; i < 12; i++) {
                    const currentMonth = (startMonth + i) % 12 || 12; // เลขเดือน
                    const currentYear = startYear + Math.floor((startMonth + i - 1) / 12); // ปี
                    // ถ้าเดือนและปีของ currentMonth เกินกว่า startMonth และ startYear
                    if (currentYear > startYear || (currentYear === startYear && currentMonth >= startMonth)) {
                        const optionValue = `${currentYear}-${currentMonth.toString().padStart(2, '0')}-01`;
                        const option = document.createElement('option');
                        option.value = optionValue;
                        option.text = `${currentYear}-${currentMonth.toString().padStart(2, '0')}`;
                        endDateInput.appendChild(option);
                    }
                }
                // ตั้งค่าค่าต่ำสุดสำหรับเดือนและปีสิ้นสุด
                endDateInput.setAttribute('min', startDateValue);
                // ตรวจสอบค่าเดิมและตั้งค่าใหม่ถ้ายังคงเป็นค่าที่ถูกต้อง
                if (currentEndDateValue && currentEndDateValue >= startDateValue) {
                    endDateInput.value = currentEndDateValue; // ตั้งค่าคงเดิม
                } else {
                    endDateInput.value = ''; // หากไม่ถูกต้องให้รีเซ็ต
                }
            } else {
                // หากไม่มีค่าในฟิลด์เริ่มต้น ให้ล้างค่าฟิลด์สิ้นสุด
                endDateInput.value = '';
                endDateInput.innerHTML = '<option value="">เลือกเดือน</option>'; // ล้างตัวเลือก
                endDateInput.removeAttribute('min');
            }
        }
        startDateInput.addEventListener('change', function () {
            updateEndDateOptions();
        });
        endDateInput.addEventListener('change', function () {
            const startDateValue = startDateInput.value;
            if (this.value < startDateValue) {
                alert('ไม่สามารถเลือกเดือนและปีสิ้นสุดที่ต่ำกว่าหรือเก่ากว่าเดือนและปีเริ่มต้นได้');
                this.value = ''; // รีเซ็ตค่าเดือนสิ้นสุด
            }
        });
        // อัปเดตค่าเดือนและปีสิ้นสุดเมื่อโหลดหน้า
        updateEndDateOptions();
    });
</script>

@endsection