@extends('layouts.app')

@section('content')
<div class="container mb-3">
    <h1 class="mb-4">รายการคำขออนุญาต </h1>
    <!-- ฟิลด์ค้นหา -->
    <form method="GET" action="{{ route('documents.OPsearch') }}" class="mb-4 d-flex align-items-center"
        id="searchForm">
        <input type="text" name="search" class="form-control" placeholder="ค้นหาผู้ขอ หรือ วัตถุประสงค์"
            value="{{ request()->get('search') }}">
        <button class="btn btn-primary ms-2" type="submit">ค้นหา</button>
        <div class="ms-2 d-flex">
            <select name="month" class="form-select w-auto" onchange="document.getElementById('searchForm').submit()">
                <option value="">เลือกเดือน</option>
                @foreach (range(1, 12) as $month)
                    <option value="{{ $month }}" {{ request()->get('month') == $month ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>
            <select name="year" class="form-select w-auto ms-2"
                onchange="document.getElementById('searchForm').submit()">
                <option value="">เลือกปี</option>
                @for ($year = date('Y') + 543; $year >= 2543; $year--)
                    <option value="{{ $year - 543 }}" {{ request()->get('year') == ($year - 543) ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endfor
            </select>
        </div>
    </form>  
    @if($documents->isEmpty())
        <div class="alert alert-info">
            {{ __('ไม่มีฟอร์มสำหรับการตรวจสอบ') }}
        </div>
    @else
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center" style="width: 190px">ผู้ขอ</th>
                    <th class="text-center" style="width: 190px">วัตถุประสงค์</th>
                    <th class="text-center">วันที่เดินทางไป</th>
                    <th class="text-center">สถานะ</th>
                    <th class="text-center">คนขับรับทราบงาน</th>
                    <th class="text-center">รายละเอียด</th>
                    <th class="text-center">รายงานคนขับ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $document)
                    <tr>
                        <td class="text-center">{{ $document->document_id }}</td>
                        <td class="text-center">
                            @foreach($document->reqDocumentUsers as $reqDocumentUser)
                                {{ $reqDocumentUser->name }} {{ $reqDocumentUser->lname }}<br>
                            @endforeach
                        </td>
                        <td class="text-center">{{ $document->objective }}</td>
                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($document->start_date)->format('d') }}
                            {{ \Carbon\Carbon::parse($document->start_date)->locale('th')->translatedFormat('F') }} พ.ศ. 
                            {{ \Carbon\Carbon::parse($document->start_date)->format('Y') + 543 }}                                                    <br>
                            เวลา : {{ \Carbon\Carbon::parse($document->start_time)->format('H:i') }} น.
                        </td>
                        <td class="text-center">
                        @if ($document->cancel_admin == 'Y' && $document->cancel_director == 'Y')
                                <span class="badge bg-secondary">รายการคำขอถูกยกเลิกแล้ว</span>
                            @elseif ($document->edit_allowed != null && $document->edit_by != 1)
                                <span class="badge bg-info">รอการแก้ไขเอกสารโดยแอดมิน</span>
                            @elseif ($document->cancel_allowed == 'pending')
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
                        
                        <td class="text-center">
                        @if ( $document->car_type == 'รถเช่า')
                            รถเช่า
                        @else
                            @if ( $document->cancel_allowed == 'pending' )
                                @if (in_array(auth()->user()->role_id, [12]))
                                    @if ($document->allow_carman == 'approved')
                                        <span class="badge bg-success">รับทราบ </span>
                                    @elseif ($document->allow_carman	 == 'pending')
                                        <span class="badge bg-warning">รอดำเนินการ</span>
                                    @else
                                        <span class="badge bg-danger">ไม่สามารถรับงานได้</span>
                                    @endif 
                                @endif
                            <!-- ยกเลิกก่อนถึงผอ. -->
                            @elseif ( $document->allow_director == 'pending' && $document->cancel_reason != null )
                                @if ( $document->cancel_admin == 'Y' )
                                    <span class="badge bg-secondary">รายการคำขอถูกยกเลิกแล้ว</span>  
                                @else
                                    <span class="badge bg-info">อยู่ระหว่างการยกเลิกคำขอ</span>  
                                @endif
                            <!-- ผอ.อนุมัติไปแล้ว -->
                            @elseif ( $document->allow_director != 'pending' && $document->cancel_reason != null )
                                @if ( $document->cancel_admin != 'Y' )
                                    <span class="badge bg-info">อยู่ระหว่างการยกเลิกคำขอ</span>  
                                @elseif ( $document->cancel_admin == 'Y' && $document->cancel_director != 'Y')
                                    <span class="badge bg-info">อยู่ระหว่างการยกเลิกคำขอ</span>  
                                @elseif ( $document->cancel_admin == 'Y' && $document->cancel_director == 'Y')
                                    <span class="badge bg-secondary">รายการคำขอถูกยกเลิกแล้ว</span>
                                @endif
                            @else
                                <span class="badge bg-secondary">รายการคำขอถูกยกเลิกแล้ว</span>
                            @endif
                        @endif
                            
                        </td>
                        <td class="text-center">
                            <a href="{{ route('documents.show') }}?id={{ $document->document_id }}" class="btn 
                                @if (($document->allow_director != 'approved' && $document->cancel_admin == 'Y') || 
                                        ($document->allow_director == 'approved' && $document->cancel_admin == 'Y' && $document->cancel_director == 'Y'))
                                    btn-secondary
                                @else
                                    btn-primary
                                @endif"> ดูรายละเอียด
                            </a>
                        </td>
                        <td class="text-center">
                            <!-- แสดงปุ่มดู PDF หากมี ReportFormance -->
                            @if($document->reportFormance)
                                <a href="{{ route('report.showRepDoc.pdf', ['id' => $document->reportFormance->report_id]) }}"
                                    class="btn btn-info" target="_blank">
                                    ดู PDF
                                </a>
                            @else
                                <span>ไม่มีรายงาน</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @endif
    {{ $documents->appends(request()->query())->links() }}
</div>
@endsection