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

                    <div class="card mb-4 shadow-sm border-1">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">{{ __('อัพเดตสถานะเอกสาร') }}</h6>
                        </div>
                        <div class="card-body">
                            @if (in_array(auth()->user()->role_id, [4, 5, 6, 7, 8, 9, 10]))
                                <label>{{ __('ความคิดเห็นหัวหน้าฝ่าย:') }}</label>
                                <div class="d-flex">
                                    <div class="form-check me-3"> <!-- เพิ่ม margin-end เพื่อให้มีระยะห่าง -->
                                        <input class="form-check-input" type="radio" name="statusdivision" value="approved"
                                            id="approve_division">
                                        <label class="form-check-label" for="approve_division">{{ __('อนุญาต') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="statusdivision" value="rejected"
                                            id="reject_division">
                                        <label class="form-check-label" for="reject_division">{{ __('ไม่อนุญาต') }}</label>
                                    </div>
                                </div>
                            @elseif (in_array(auth()->user()->role_id, [13, 14, 15, 16]))
                                <label>{{ __('ความคิดเห็นหัวหน้างานวิจัย:') }}</label>
                                <div class="d-flex">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="radio" name="statusdepartment" value="approved"
                                            id="approve_department">
                                        <label class="form-check-label" for="approve_department">{{ __('อนุญาต') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="statusdepartment" value="rejected"
                                            id="reject_department">
                                        <label class="form-check-label" for="reject_department">{{ __('ไม่อนุญาต') }}</label>
                                    </div>
                                </div>
                            @elseif (in_array(auth()->user()->role_id, [12]))
                                <label>{{ __('ความคิดเห็นคนสั่งรถ:') }}</label>
                                <div class="d-flex">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="radio" name="statusopcar" value="approved"
                                            id="approve_opcar">
                                        <label class="form-check-label" for="approve_opcar">{{ __('อนุญาต') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="statusopcar" value="rejected"
                                            id="reject_opcar">
                                        <label class="form-check-label" for="reject_opcar">{{ __('ไม่อนุญาต') }}</label>
                                    </div>
                                </div>
                            @elseif (in_array(auth()->user()->role_id, [2]))
                                <label>{{ __('ความคิดเห็นหัวหน้าสำนักงาน:') }}</label>
                                <div class="d-flex">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="radio" name="statusofficer" value="approved"
                                            id="approve_officer">
                                        <label class="form-check-label" for="approve_officer">{{ __('อนุญาต') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="statusofficer" value="rejected"
                                            id="reject_officer">
                                        <label class="form-check-label" for="reject_officer">{{ __('ไม่อนุญาต') }}</label>
                                    </div>
                                </div>
                            @elseif (in_array(auth()->user()->role_id, [3]))
                                <label>{{ __('ความคิดเห็นผู้อำนวยการ:') }}</label>
                                <div class="d-flex">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="radio" name="statusdirector" value="approved"
                                            id="approve_director">
                                        <label class="form-check-label" for="approve_director">{{ __('อนุญาต') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="statusdirector" value="rejected"
                                            id="reject_director">
                                        <label class="form-check-label" for="reject_director">{{ __('ไม่อนุญาต') }}</label>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">{{ __('บันทึก') }}</button>
                        </div>
                    </div>
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
                                                $names = preg_split('/[\s,]+/', $document->companion_name);
                                                $visibleCount = 3; // จำนวนชื่อที่ต้องการแสดง
                                            @endphp

                                            @foreach($names as $index => $name)
                                                @if($index < $visibleCount)
                                                    <li>{{ trim($name) }}</li>
                                                @endif
                                            @endforeach

                                            @if(count($names) > $visibleCount)
                                                <li>
                                                    <a data-bs-toggle="collapse" href="#moreCompanions" role="button"
                                                        aria-expanded="false" aria-controls="moreCompanions">
                                                        {{ __('ดูเพิ่มเติม') }} ({{ count($names) - $visibleCount }}
                                                        {{ __('คนเพิ่มเติม') }})
                                                    </a>
                                                    <div class="collapse" id="moreCompanions">
                                                        <ul class="list-unstyled mt-2">
                                                            @foreach($names as $index => $name)
                                                                @if($index >= $visibleCount)
                                                                    <li>{{ trim($name) }}</li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </li>
                                            @endif
                                        </ul>
                                    </td>
                                    <td>
                                        <strong>{{ __('ผู้ร่วมเดินทางทั้งหมด') }}:</strong> {{ $document->sum_companion }}
                                    </td>
                                    
                                </tr>
                                <tr>
                                    <td><strong>{{ __('วันที่ไป') }}:</strong>
                                        {{ optional($document->start_date) ? \Carbon\Carbon::parse($document->start_date)->format('d-m-Y') : 'N/A' }}
                                    </td>
                                    <td><strong>{{ __('วันที่กลับ') }}:</strong>
                                        {{ optional($document->end_date) ? \Carbon\Carbon::parse($document->end_date)->format('d-m-Y') : 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{{ __('เวลาไป') }}:</strong> {{ $document->start_time ?? 'N/A' }}</td>
                                    <td><strong>{{ __('เวลากลับ') }}:</strong> {{ $document->end_time ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- ข้อมูลสถานที่ -->
                        <div class="mb-3 border p-3">
                            <h6 class="text-muted">{{ __('ข้อมูลสถานที่') }}</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>{{ __('สถานที่') }}:</strong> {{ $document->location ?? 'N/A' }}</td>
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
                                    <a href="{{ Storage::url($document->related_project) }}" target="_blank"
                                        class="btn btn-outline-primary">{{ __('ดูไฟล์') }}</a>
                                @else
                                    {{ __('ไม่มีไฟล์') }}
                                @endif
                            </p>
                        </div>

                        <!-- ลงชื่อผู้ขอ -->
                        <div class="mt-4" style="text-align: right; margin-right: 50px;">
                            <strong>{{ __('ลงชื่อผู้ขอ:') }}</strong>
                            <p>{{ optional($document->reqDocumentUsers->first())->signature_name ?? 'N/A' }}</p>
                        </div>

                    </div>

        @endforeach
    @endif
        </div>
        @endsection