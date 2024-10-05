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
                <div class="mb-3 border p-3">
                    <h6 class="text-muted">{{ __('ข้อมูลการเดินทาง') }}</h6>
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>{{ __('วัตถุประสงค์') }}:</strong> {{ $document->objective }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('ผู้ร่วมเดินทาง') }}:</strong>
                                <ul class="list-unstyled">
                                    <!-- ใช้ preg_split() เพื่อแยกชื่อผู้ร่วมเดินทาง -->
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
                            <td><strong>{{ __('ผู้ร่วมเดินทางทั้งหมด') }}:</strong> {{ $document->sum_companion }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('วันที่ไป') }}:</strong>
                                {{ \Carbon\Carbon::parse($document->start_date)->format('d-m-Y') }}</td>
                            <td><strong>{{ __('วันที่กลับ') }}:</strong>
                                {{ \Carbon\Carbon::parse($document->end_date)->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('เวลาไป') }}:</strong> {{ $document->start_time }}</td>
                            <td><strong>{{ __('เวลากลับ') }}:</strong> {{ $document->end_time }}</td>
                        </tr>
                    </table>
                </div>

                <!-- ข้อมูลสถานที่ -->
                <div class="mb-3 border p-3">
                    <h6 class="text-muted">{{ __('ข้อมูลสถานที่') }}</h6>
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>{{ __('สถานที่') }}:</strong> {{ $document->location }}</td>
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
                    <p>{{ $document->reqDocumentUsers->first()->signature_name ?? 'N/A' }}</p>
                </div>

            </div>
        </div>
    @endif
</div>
@endsection