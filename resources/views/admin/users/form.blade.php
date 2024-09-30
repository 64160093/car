<!-- resources/views/document-history.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
    @if (auth()->user()->is_admin == 1)
        <h2>รายการคำขอทั้งหมด</h2>
    @else        
        <h2>ประวัติการยื่นขอ</h2>
    @endif

    @if($documents->isEmpty())
        <div class="alert alert-info">
            {{ __('ไม่มีฟอร์มสำหรับการตรวจสอบ') }}
        </div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ชื่อ-นามสกุล</th>
                    <th>วัตถุประสงค์</th>
                    <th>วันที่เดินทางไป</th>
                    <th>วันที่เดินทางกลับ</th>
                    <th>division</th>
                    <th>ขอdepartment</th>
                    <th>PDF</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents->groupBy(function($date) {
                    return \Carbon\Carbon::parse($date->created_at)->format('F Y');
                }) as $month => $groupedDocuments)
                    @foreach($groupedDocuments as $document)
                    <tr>
                            @php
                                $requester = $document->reqDocumentUsers->first();
                            @endphp
                        <td>{{ $requester->name }} {{ $requester->lname }}</td> <!-- ชื่อผู้ใช้ -->
                        <td>{{ $document->objective }}</td> <!-- วัตถุประสงค์ -->
                        <td>
                            {{ \Carbon\Carbon::parse($document->start_date)->format('d F Y') }}<br>
                            เวลา : {{ \Carbon\Carbon::parse($document->start_time)->format('H:i') }} น.
                        </td> <!-- วันที่เดินทางไป -->
                        <td>
                            {{ \Carbon\Carbon::parse($document->end_date)->format('d F Y') }}<br>
                            เวลา : {{ \Carbon\Carbon::parse($document->end_time)->format('H:i') }} น.
                        </td> <!-- วันที่เดินทางกลับ -->
                        <td>
                            @if ($document->allow_division == 'approved')
                                <span class="badge bg-success">อนุมัติ</span>
                            @elseif ($document->allow_division == 'pending')
                                <span class="badge bg-warning">รอดำเนินการ</span>
                            @else
                                <span class="badge bg-danger">ถูกปฏิเสธ</span>
                            @endif
                        </td>
                        <td>
                            @if ($document->allow_department == 'approved')
                                <span class="badge bg-success">อนุมัติ</span>
                            @elseif ($document->allow_department == 'pending')
                                <span class="badge bg-warning">รอดำเนินการ</span>
                            @else
                                <span class="badge bg-danger">ถูกปฏิเสธ</span>
                            @endif
                        </td>
                        <td>
                            
                        </td> <!-- ปุ่มดาวน์โหลด PDF -->
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endif
    </div>
@endsection
