@extends('layouts.app')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')

<div class="container">
    <h1 class="text text-center">แผนงานในการปฏิบัติหน้าที่ของคนขับรถ</h1>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- ตรวจสอบว่ามีแผนงานหรือไม่ -->
    @if($documents->isEmpty())
        <div class="alert alert-info">
            {{ __('ไม่มีฟอร์มสำหรับการตรวจสอบ') }}
        </div>
    @else
        <!-- แสดงข้อมูลแผนงาน -->
        <table class="table table-bordered text-center"> <!-- เพิ่ม text-center ที่นี่เพื่อจัดกึ่งกลางทั้งตาราง -->
            <thead>
                <tr>
                    <th class="text-center">วันที่</th>
                    <th class="text-center">เวลา</th>
                    <th class="text-center">รายละเอียด</th>
                    <th class="text-center">รายละเอียด</th>
                    <th class="text-center">รายงาน</th>
                    <th class="text-center">ดูรายงานที่ส่งแล้ว</th>
                    <th class="text-center">ดู PDF</th> <!-- เพิ่มคอลัมน์สำหรับดู PDF -->
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $document)
                        <tr>
                            <!-- วันที่ -->
                            <td class="align-middle">{{ \Carbon\Carbon::parse($document->start_date)->format('d F Y') }}</td>

                            <!-- เวลา -->
                            <td class="align-middle">{{ \Carbon\Carbon::parse($document->start_time)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($document->end_time)->format('H:i') }}
                            </td>

                            <!-- รายละเอียด -->
                            <td class="align-middle">{{ $document->objective }}</td>

                            <!-- รายละเอียดเพิ่มเติม -->
                            <td class="align-middle">
                                <a href="{{ route('documents.show', ['id' => $document->document_id]) }}" class="btn btn-primary">
                                    <i class="fas fa-info-circle"></i> รายละเอียด
                                </a>
                            </td>

                            <!-- รายงาน -->
                            <td class="align-middle">
                                @php
                                    // ตรวจสอบว่ามีรายงานที่ถูกส่งแล้วหรือไม่
                                    $report = \App\Models\ReportFormance::where('req_document_id', $document->document_id)->first();
                                @endphp

                                @if(!$report)
                                    <a href="{{ route('report.index', ['id' => $document->document_id]) }}" class="btn btn-warning">
                                        <i class="bi bi-file-text-fill"></i> รายงาน
                                    </a>
                                @else
                                    <span class="text-success">ส่งรายงานแล้ว</span>
                                @endif
                            </td>

                            <!-- ดูรายงานที่ส่งแล้ว -->
                            <td class="align-middle">
                                @if($report)
                                    <a href="{{ route('reportdoc.show', ['id' => $report->report_id]) }}" class="btn btn-success">
                                        <i class="bi bi-eye-fill"></i> ดูรายงานที่ส่งแล้ว
                                    </a>
                                @else
                                    <span class="text-muted">ยังไม่ได้ส่งรายงาน</span>
                                @endif
                            </td>

                            <!-- ดู PDF -->
                            <td class="align-middle">
                                @if($report)
                                    <a href="{{ route('report.showRepDoc.pdf', ['id' => $report->report_id]) }}" class="btn btn-info" 
                                        target="_blank">
                                        ดู PDF
                                    </a>
                                @else
                                    <span class="text-muted">ยังไม่ได้สร้างรายงาน</span>
                                @endif
                            </td>
                        </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection