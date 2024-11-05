@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>{{ __('หน้าตรวจสอบฟอร์ม') }}</h1>

    @if(!$document)
        <div class="alert alert-info">
            {{ __('ไม่มีข้อมูลเอกสาร') }}
        </div>
    @else
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">{{ __('เอกสารที่: ') . $document->document_id }}</h5>
                <p class="mb-0">
                    {{ __('วันที่ทำเรื่อง: ') .
            \Carbon\Carbon::parse($document->reservation_date)->format('d') . ' ' .
            \Carbon\Carbon::parse($document->reservation_date)->locale('th')->translatedFormat('F') . ' พ.ศ. ' .
            \Carbon\Carbon::parse($document->reservation_date)->format('Y') 
                                                }}
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
                            <td><strong>{{ __('วัตถุประสงค์') }}:</strong> {{ $document->objective }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('ผู้ร่วมเดินทาง') }}:</strong>
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
                            <td><strong>{{ __('ผู้ร่วมเดินทางทั้งหมด') }}:</strong> {{ count($companions) }} คน</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('ผู้ควบคุมรถ') }}:</strong>
                                {{ $document->carController->name ?? 'N/A' }} {{ $document->carController->lname ?? 'N/A' }}
                            </td>

                        </tr>
                        <tr>
                            <td><strong>{{ __('วันที่ไป') }}:</strong>
                                {{ 
                                                    \Carbon\Carbon::parse($document->start_date)->format('d') . ' ' .
            \Carbon\Carbon::parse($document->start_date)->locale('th')->translatedFormat('F') . ' พ.ศ. ' .
            \Carbon\Carbon::parse($document->start_date)->addYears(543)->format('Y') 
                                                }}</td>
                            <td><strong>{{ __('วันที่กลับ') }}:</strong>
                                {{ 
                                                    \Carbon\Carbon::parse($document->end_date)->format('d') . ' ' .
            \Carbon\Carbon::parse($document->end_date)->locale('th')->translatedFormat('F') . '  พ.ศ.  ' .
            \Carbon\Carbon::parse($document->end_date)->addYears(543)->format('Y') 
                                                }}
                        </tr>
                        <tr>
                            <td><strong>{{ __('เวลาไป') }}:</strong> {{ $document->start_time }}</td>
                            <td><strong>{{ __('เวลากลับ') }}:</strong> {{ $document->end_time }}</td>
                        </tr>
                    </table>
                </div>

                <!-- ข้อมูลสถานที่ -->
                <h6 class="text-muted">{{ __('ข้อมูลสถานที่') }}</h6>
                <div class="mb-3 border p-3">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>{{ __('สถานที่') }}:</strong> {{ $document->location }}</td>
                            <td><strong>{{ __('ให้รถไปรับที่ ') }}:</strong> {{ $document->car_pickup }}</td>
                            <td><strong>{{ __('รถประเภท') }}:</strong> {{ $document->car_type }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('จังหวัด') }}:</strong> {{ $document->province->name_th ?? 'N/A' }}</td>
                            <td><strong>{{ __('อำเภอ') }}:</strong> {{ $document->amphoe->name_th ?? 'N/A' }}</td>
                            <td><strong>{{ __('ตำบล') }}:</strong> {{ $document->district->name_th ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>

                <!-- โครงการที่เกี่ยวข้อง -->
                <h6 class="text-muted">{{ __('โครงการที่เกี่ยวข้อง') }}</h6>
                <div class="mt-2 border p-3">
                    <p class="form-control-static mt-0 mb-0">
                    @if($document->related_project)
                            <a href="{{ asset('storage/' . $document->related_project) }}" target="_blank"
                                class="btn btn-outline-primary">{{ __('ดูไฟล์') }}</a>
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
        </div>
    @endif
</div>
@endsection