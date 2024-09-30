@extends('layouts.app')

@section('content')
<div class="container">
    <h2>ประวัติการยื่นขอ</h2>

    {{-- ปุ่มสำหรับเลือกการกรอง --}}

    <div class="mb-4 d-flex justify-content-end">
        <div class="d-flex align-items-center">

            <div class="btn-group" role="group">
                <a href="{{ route('documents.history', ['filter' => 'reservation']) }}"
                    class="btn {{ request('filter') == 'reservation' || !request('filter') ? 'btn-primary' : 'btn-outline-primary' }}">
                    เดือนที่ทำเรื่อง
                </a>
                <a href="{{ route('documents.history', ['filter' => 'travel']) }}"
                    class="btn {{ request('filter') == 'travel' ? 'btn-primary' : 'btn-outline-primary' }}">
                    เดือนที่ไป
                </a>
            </div>
        </div>
    </div>

    @if($documents->isEmpty())
        <div class="alert alert-info">
            {{ __('ไม่มีฟอร์มสำหรับการตรวจสอบ') }}
        </div>
    @else
        {{-- จัดกลุ่มเอกสารตามประเภทที่เลือก --}}
        @php
            $groupedBy = request('filter') == 'travel' ? 'start_date' : 'reservation_date';
        @endphp

        @foreach($documents->groupBy(function ($date) use ($groupedBy) {
                return \Carbon\Carbon::parse($date->$groupedBy)->format('F Y');
            }) as $month => $groupedDocuments)
            <div class="card mb-3">
                {{-- Header ของการ์ดจะแสดงเดือน --}}
                <div class="card-header">{{ $month }}</div>
                <div class="card-body">
                    {{-- Loop เอกสารแต่ละรายการในแต่ละเดือน --}}
                    @foreach($groupedDocuments as $document)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center"
                                    style="background-color: #FFC107; padding: 10px;">
                                    <div>
                                        <strong>วัตถุประสงค์: {{ $document->objective }}</strong><br>
                                        สถานที่: {{ $document->location }}<br>
                                        {{-- วันที่ทำเรื่อง จาก reservation_date --}}
                                        วันที่ทำเรื่อง:
                                        {{ \Carbon\Carbon::parse($document->reservation_date)->translatedFormat('d F Y') }}<br>
                                        {{-- วันที่ไปและวันที่กลับ --}}
                                        วันที่ไป: {{ \Carbon\Carbon::parse($document->start_date)->translatedFormat('d F Y') }}<br>
                                        วันที่กลับ: {{ \Carbon\Carbon::parse($document->end_date)->translatedFormat('d F Y') }}<br>
                                        เวลาไป: {{ \Carbon\Carbon::parse($document->start_time)->format('H:i') }} น.<br>
                                        เวลากลับ: {{ \Carbon\Carbon::parse($document->end_time)->format('H:i') }} น.<br>
                                    </div>
                                    <div>
                                        {{-- ปุ่มเพื่อดูรายละเอียดของเอกสาร --}}
                                        <a href="{{ route('documents.review', ['id' => $document->id]) }}" class="btn btn-primary">ดูรายละเอียด</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection