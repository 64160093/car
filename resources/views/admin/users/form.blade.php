@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container">
    @if (auth()->user()->is_admin == 1)
        <h2>รายการคำขอทั้งหมด</h2>
    @endif

    <!-- ช่องค้นหาข้อมูล -->
    <div class="container-fluid mt-2">
        <form class="d-flex" method="GET" action="{{ route('admin.users.searchform') }}">
            <input type="search" id="searchName" name="q" class="form-control me-2" placeholder="ค้นหาข้อมูล"
                aria-label="Search" value="{{ request()->get('q') }}">
            <button type="submit" class="btn btn-primary">ค้นหา</button>
            {{-- Dropdown สำหรับกรองสถานะ --}}
            <select name="filter" id="filter" class="form-select me-2" style="max-width: 200px;"
                onchange="this.form.submit()">
                <option value="">ทั้งหมด</option>
                <option value="completed" {{ request('filter') == 'completed' ? 'selected' : '' }}>สำเร็จแล้ว</option>
                <option value="pending" {{ request('filter') == 'pending' ? 'selected' : '' }}>พิจารณา</option>
                <option value="cancelled" {{ request('filter') == 'cancelled' ? 'selected' : '' }}>ไม่อนุมัติ/ยกเลิก
                </option>
            </select>
        </form>
    </div>

    <!-- ตารางแสดงข้อมูลเอกสาร -->
    @if($documents->isEmpty())
        <div class="alert alert-info">
            {{ __('ไม่มีฟอร์มสำหรับการตรวจสอบ') }}
        </div>
    @else
        <div class="table-responsive mt-4 mb-4">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr class="text-center">
                        <th>#</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>วัตถุประสงค์</th>
                        <th>วันที่เดินทางไป</th>
                        <th>วันที่เดินทางกลับ</th>
                        <th>สถานะปัจจุบัน</th>
                        <th>ดูสถานะทั้งหมด</th>
                        <th>PDF คำร้อง/คนขับ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents->groupBy(function ($date) {
                            return \Carbon\Carbon::parse($date->created_at)->format('F Y');
                        }) as $month => $groupedDocuments)
                                    @foreach($groupedDocuments as $document)
                                            <tr class="text-center">
                                                @php
                                                    $requester = $document->reqDocumentUsers->first();
                                                @endphp
                                                <td>{{ $requester->id }}</td>
                                                <td>{{ $requester->name }} {{ $requester->lname }}</td>
                                                <td style="max-width: 160px; ">{{ $document->objective }}</td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($document->start_date)->format('d') }}
                                                    {{ \Carbon\Carbon::parse($document->start_date)->locale('th')->translatedFormat('F') }}
                                                    {{ \Carbon\Carbon::parse($document->start_date)->format('Y') + 543 }}<br>
                                                    เวลา : {{ \Carbon\Carbon::parse($document->start_time)->format('H:i') }} น.
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($document->end_date)->format('d') }}
                                                    {{ \Carbon\Carbon::parse($document->end_date)->locale('th')->translatedFormat('F') }}
                                                    {{ \Carbon\Carbon::parse($document->end_date)->format('Y') + 543 }}<br>
                                                    เวลา : {{ \Carbon\Carbon::parse($document->end_time)->format('H:i') }} น.
                                                </td>
                                                <td>
                                                    @if ($document->cancel_allowed == 'pending')
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
                                                    @else
                                                        <span class="badge bg-secondary">รายการคำขอถูกยกเลิกแล้ว</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    <a href="{{ route('documents.status') }}?id={{ $document->document_id }}"
                                                        class="btn btn-outline-primary">สถานะ</a>
                                                </td>
                                                <td>
                                                    @if ($document->allow_director != 'pending')
                                                        <a href="{{ route('PDF.document') }}?id={{ $document->document_id }}"
                                                            class="btn btn-outline-primary"> PDF
                                                        </a>
                                                    @else
                                                        <button type="button" class="btn btn-secondary" disabled>PDF</button>
                                                    @endif
                                                </td>
                                            </tr>
                                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection