@extends('layouts.app')

@section('content')
<div class="container">
    <h1>ตรวจสอบฟอร์ม</h1>

    <div class="card">
        <div class="card-header">รายละเอียดฟอร์ม</div>
        <div class="card-body">
            <p><strong>ชื่อผู้ติดตาม:</strong> {{ $document->companion_name }}</p>
            <p><strong>วัตถุประสงค์:</strong> {{ $document->objective }}</p>
            <p><strong>สถานที่:</strong> {{ $document->location }}</p>
            <p><strong>วันที่จองรถ:</strong> {{ $document->reservation_date }}</p>
            <p><strong>วันที่เริ่มเดินทาง:</strong> {{ $document->start_date }}</p>
            <p><strong>วันที่สิ้นสุดเดินทาง:</strong> {{ $document->end_date }}</p>
            <p><strong>เวลาเริ่มเดินทาง:</strong> {{ $document->start_time }}</p>
            <p><strong>เวลาสิ้นสุดเดินทาง:</strong> {{ $document->end_time }}</p>
            <p><strong>จำนวนผู้ติดตาม:</strong> {{ $document->sum_companion }}</p>
            <p><strong>ประเภทของรถ:</strong> {{ $document->car_type }}</p>
            <p><strong>จังหวัด:</strong> {{ $document->province->name_th }}</p>
            <p><strong>อำเภอ:</strong> {{ $document->amphoe->name_th }}</p>
            <p><strong>ตำบล:</strong> {{ $document->district->name_th }}</p>
            <p><strong>ประเภทงาน:</strong> {{ $document->workType->name }}</p>
            <!-- หากมีไฟล์ที่แนบ -->
            @if ($document->related_project)
                <p><strong>เอกสารที่แนบ:</strong> <a href="{{ Storage::url($document->related_project) }}" target="_blank">ดูเอกสาร</a></p>
            @endif
        </div>
    </div>

    <div class="mt-4">
        <form action="{{ route('documents.approve', $document->id) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-success">อนุมัติ</button>
        </form>
        <form action="{{ route('documents.reject', $document->id) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-danger">ไม่อนุมัติ</button>
        </form>
    </div>
</div>
@endsection
