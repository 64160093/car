@extends('layouts.app')

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
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>วันที่</th>
                    <th>เวลา</th>
                    <th>รายละเอียด</th>
                    <th>สถานะ</th>
                    <th>รายละเอียด</th>
                </tr>
            </thead>
            <tbody>
            @foreach($documents as $document)
                <tr>
                    <!-- วันที่ -->
                    <td>{{ \Carbon\Carbon::parse($document->start_date)->format('d F Y') }}</td>
                    
                    <!-- เวลา -->
                    <td>{{ \Carbon\Carbon::parse($document->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($document->end_time)->format('H:i') }}</td>

                    <!-- รายละเอียด -->
                    <td>{{ $document->objective }}</td>

                    <!-- สถานะ -->
                    <td>
                        @if ($document->status == 'approved')
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle"></i> อนุมัติ
                            </span>
                        @elseif ($document->status == 'pending')
                            <span class="badge bg-warning">
                                <i class="fas fa-clock"></i> รอดำเนินการ
                            </span>
                        @else
                            <span class="badge bg-danger">
                                <i class="fas fa-times-circle"></i> ถูกปฏิเสธ
                            </span>
                        @endif
                    </td>

                    <!-- รายละเอียดเพิ่มเติม -->
                    <td>
                        <a href="{{ route('documents.show', ['id' => $document->document_id]) }}" class="btn btn-primary">
                            <i class="fas fa-info-circle"></i> รายละเอียด
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
