@extends('layouts.app')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')

<div class="container">
    <h1 class="text text-center mb-4">แผนงานในการปฏิบัติหน้าที่ของคนขับรถ</h1>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- ฟิลด์ค้นหา -->
    <form method="GET" action="{{ route('documents.scheduleSearch') }}" class="mb-4 d-flex align-items-center"
        id="searchForm">
        <input type="text" name="search" class="form-control" placeholder="ค้นหาวัตถุประสงค์"
            value="{{ request()->get('search') }}">
        <button class="btn btn-primary ms-2" type="submit">ค้นหา</button>

        <div class="ms-2 d-flex"> <!-- Div เพื่อจัดกลุ่มเดือนและปี -->
            <!-- ตัวเลือกเดือน -->
            <select name="month" class="form-select w-auto" onchange="document.getElementById('searchForm').submit()">
                <option value="">เลือกเดือน</option>
                @foreach (range(1, 12) as $month)
                    <option value="{{ $month }}" {{ request()->get('month') == $month ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>

            <!-- ตัวเลือกปี -->
            <select name="year" class="form-select w-auto ms-2"
                onchange="document.getElementById('searchForm').submit()">
                <option value="">เลือกปี</option>
                @for ($year = date('Y') + 543; $year >= 2543; $year--)
                    <option value="{{ $year - 543 }}" {{ request()->get('year') == ($year - 543) ? 'selected' : '' }}>
                        {{ $year }} <!-- แสดงปี พ.ศ. -->
                    </option>
                @endfor
            </select>
        </div>
    </form>




    <!-- ตรวจสอบว่ามีแผนงานหรือไม่ -->
    @if($documents->isEmpty())
        <div class="alert alert-info">
            {{ __('ไม่มีฟอร์มสำหรับการตรวจสอบ') }}
        </div>
    @else
        <!-- แสดงข้อมูลแผนงาน -->
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th class="text-center">วันที่เดินทาง</th>
                    <th class="text-center">วัตถุประสงค์</th>
                    <th class="text-center">เวลา</th>
                    <th class="text-center">สถานะ</th>
                    <th class="text-center">รายละเอียด</th>
                    <th class="text-center">รายงาน</th>
                    <th class="text-center">ดูรายงานที่ส่งแล้ว</th>
                    <th class="text-center">ดู PDF</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $document)
                        <tr>
                            <td class="align-middle">
                                {{ \Carbon\Carbon::parse($document->start_date)->format('d') }}
                                {{ \Carbon\Carbon::parse($document->start_date)->locale('th')->translatedFormat('F') }} พ.ศ. 
                                {{ \Carbon\Carbon::parse($document->start_date)->format('Y') + 543 }}                                                    <br>
                            </td>
                            <td class="align-middle">{{ $document->objective }}</td>
                            <td class="align-middle">{{ \Carbon\Carbon::parse($document->start_time)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($document->end_time)->format('H:i') }}
                            </td>
                            <td class="align-middle">
                                @if ($document->allow_carman == 'approved')
                                    <span class="badge bg-success">รับทราบงาน</span>
                                @elseif ($document->allow_carman == 'pending')
                                    <span class="badge bg-warning">รอดำเนินการ</span>
                                @else
                                    <span class="badge bg-danger">ไม่สามารถรับงานได้</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <a href="{{ route('documents.show', ['id' => $document->document_id]) }}" class="btn btn-primary">
                                    <i class="fas fa-info-circle"></i> รายละเอียด
                                </a>
                            </td>
                            <td class="align-middle">
                                @php
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
                            <td class="align-middle">
                                @if($report)
                                    <a href="{{ route('reportdoc.show', ['id' => $report->report_id]) }}" class="btn btn-success">
                                        <i class="bi bi-eye-fill"></i> ดูรายงานที่ส่งแล้ว
                                    </a>
                                @else
                                    <span class="text-muted">ยังไม่ได้ส่งรายงาน</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                @if($report)
                                    <a href="{{ route('report.showRepDoc.pdf', ['id' => $report->report_id]) }}" class="btn btn-info"
                                        target="_blank">
                                        ดู PDF
                                    </a>
                                @else
                                    <span class="text-muted">ยังไม่ได้ส่งรายงาน</span>
                                @endif
                            </td>
                        </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    {{ $documents->links() }}
</div>
@endsection