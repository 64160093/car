@extends('layouts.app')

@section('content')
<div class="container mb-3">
        <h1 class="mb-4">รายการคำขออนุญาต </h1>   
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
                            {{ \Carbon\Carbon::parse($document->start_date)->locale('th')->translatedFormat('F') }}
                            {{ \Carbon\Carbon::parse($document->start_date)->format('Y') + 543 }}                                                    <br>
                            เวลา : {{ \Carbon\Carbon::parse($document->start_time)->format('H:i') }} น.
                        </td>
                        <td class="text-center">
                            @if ( $document->cancel_allowed == 'pending' )
                                @if (in_array(auth()->user()->role_id, [12]))
                                    @if ($document->allow_opcar == 'approved')
                                        <span class="badge bg-success">อนุมัติ </span>
                                    @elseif ($document->allow_opcar	 == 'pending')
                                        <span class="badge bg-warning">รอดำเนินการ</span>
                                    @else
                                        <span class="badge bg-danger">ถูกปฏิเสธ</span>
                                    @endif 
                                @endif
                            @else
                                <span class="badge bg-secondary">รายการคำขอถูกยกเลิกแล้ว</span>
                            @endif
                        </td>
                        
                        <td class="text-center">
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
                            @else
                                <span class="badge bg-secondary">รายการคำขอถูกยกเลิกแล้ว</span>
                            @endif
                        </td>
                        <td class="text-center">
                        @if ( $document->cancel_allowed == 'pending' )
                            <a href="{{ route('documents.show') }}?id={{ $document->document_id }}"
                                class="btn btn-primary">ดูรายละเอียด</a>
                        @else
                            <a href="{{ route('documents.show') }}?id={{ $document->document_id }}"
                                class="btn btn-secondary">ดูรายละเอียด</a>
                        @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @endif
</div>
@endsection