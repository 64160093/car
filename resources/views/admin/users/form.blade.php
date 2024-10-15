@extends('layouts.app')

@section('content')
<div class="container">
    @if (auth()->user()->is_admin == 1)
        <h2>รายการคำขอทั้งหมด</h2>
    @endif

    @if($documents->isEmpty())
        <div class="alert alert-info">
            {{ __('ไม่มีฟอร์มสำหรับการตรวจสอบ') }}
        </div>
    @else
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>วัตถุประสงค์</th>
                    <th>วันที่เดินทางไป</th>
                    <th>วันที่เดินทางกลับ</th>
                    <th>สถานะปัจจุบัน</th>
                    <th>ดูสถานะทั้งหมด</th>
                    <th>PDF</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents->groupBy(function ($date) {
                        return \Carbon\Carbon::parse($date->created_at)->format('F Y');
                    }) as $month => $groupedDocuments)
                            @foreach($groupedDocuments as $document)
                                <tr>
                                    @php
                                        $requester = $document->reqDocumentUsers->first();
                                    @endphp
                                    <td>{{ $requester->id }}</td>
                                    <td>{{ $requester->name }} {{ $requester->lname }}</td>
                                    <td>{{ $document->objective }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($document->start_date)->format('d F Y') }}<br>
                                        เวลา : {{ \Carbon\Carbon::parse($document->start_time)->format('H:i') }} น.
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($document->end_date)->format('d F Y') }}<br>
                                        เวลา : {{ \Carbon\Carbon::parse($document->end_time)->format('H:i') }} น.
                                    </td>
                                    <td>
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
                                    </td>

                                    <td>
                                        <a href="{{ route('documents.status') }}?id={{ $document->document_id }}"
                                            class="btn btn-outline-primary">สถานะ</a>
                                    </td>
                                    <td>
                                       รอก่อง
                                    </td>
                                </tr>
                            @endforeach
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
