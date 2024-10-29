@extends('layouts.app')

@section('content')
<div class="container">
        <h1>รายการคำขออนุญาต </h1>

        @if (in_array(auth()->user()->role_id, [4, 5, 6, 7, 8, 9, 10])) 
            <h5>สำหรับหัวหน้าฝ่าย</h5>                     
        @elseif (in_array(auth()->user()->role_id, [13, 14, 15, 16]))
            <h5>สำหรับหัวหน้างานวิจัย</h5>
        @elseif (in_array(auth()->user()->role_id, [12]))
            <h5>สำหรับคนสั่งรถ</h5>
        @elseif (in_array(auth()->user()->role_id, [2]))
            <h5>สำหรับหัวหน้าสำนักงาน</h5>
        @elseif (in_array(auth()->user()->role_id, [3]))
            <h5>สำหรับผู้อำนวยการ</h5>
        @endif 
        

    @if($documents->isEmpty())
        <div class="alert alert-info">
            {{ __('ไม่มีฟอร์มสำหรับการตรวจสอบ') }}
        </div>
    @else
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">ผู้ส่ง</th>
                    <th class="text-center">วัตถุประสงค์</th>
                    <th class="text-center">วันที่เดินทางไป</th>
                    <th class="text-center">วันที่เดินทางกลับ</th>
                    <th class="text-center">สถานะ</th>
                    <th class="text-center">รายละเอียด</th>
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
                            {{ \Carbon\Carbon::parse($document->end_date)->format('d') }}
                            {{ \Carbon\Carbon::parse($document->end_date)->locale('th')->translatedFormat('F') }}
                            {{ \Carbon\Carbon::parse($document->end_date)->format('Y') + 543 }}                                                    <br>
                            เวลา : {{ \Carbon\Carbon::parse($document->end_time)->format('H:i') }} น.
                        </td>
                        <td class="text-center">
                            @if ( $document->cancel_allowed == 'pending' )
                                @if (in_array(auth()->user()->role_id, [4, 5, 6, 7, 8, 9, 10]))
                                    @if ($document->allow_division == 'approved')
                                        <span class="badge bg-success">อนุมัติ</span>
                                    @elseif ($document->allow_division == 'pending')
                                        <span class="badge bg-warning">รอดำเนินการ</span>
                                    @else
                                        <span class="badge bg-danger">ถูกปฏิเสธ</span>
                                    @endif
                                @elseif (in_array(auth()->user()->role_id, [13, 14, 15, 16]))
                                    @if ($document->allow_department == 'approved')
                                        <span class="badge bg-success">อนุมัติ</span>
                                    @elseif ($document->allow_department	 == 'pending')
                                        <span class="badge bg-warning">รอดำเนินการ</span>
                                    @else
                                        <span class="badge bg-danger">ถูกปฏิเสธ</span>
                                    @endif 

                                @elseif (in_array(auth()->user()->role_id, [12]))
                                    @if ($document->allow_opcar == 'approved')
                                        <span class="badge bg-success">อนุมัติ</span>
                                    @elseif ($document->allow_opcar	 == 'pending')
                                        <span class="badge bg-warning">รอดำเนินการ</span>
                                    @else
                                        <span class="badge bg-danger">ถูกปฏิเสธ</span>
                                    @endif 

                                @elseif (in_array(auth()->user()->role_id, [2]))
                                    @if ($document->allow_officer == 'approved')
                                        <span class="badge bg-success">อนุมัติ</span>
                                    @elseif ($document->allow_officer	 == 'pending')
                                        <span class="badge bg-warning">รอดำเนินการ</span>
                                    @else
                                        <span class="badge bg-danger">ถูกปฏิเสธ</span>
                                    @endif 
                                
                                @elseif (in_array(auth()->user()->role_id, [3]))
                                    @if ($document->allow_director == 'approved')
                                        <span class="badge bg-success">อนุมัติ</span>
                                    @elseif ($document->allow_director	 == 'pending')
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
                                <a href="{{ route('documents.show') }}?id={{ $document->document_id }}"
                                    class="btn btn-primary">ดูรายละเอียด</a>
                            @else
                                <a href="{{ route('documents.show') }}?id={{ $document->document_id }}"
                                class="btn btn-secondary">ดูรายละเอียด</a>
                            @endif

                            @if ( in_array(auth()->user()->role_id, [3]))
                                @if ( $document->cancel_admin == 'Y')
                                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDirectorCancellationModal">
                                        !
                                    </button>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @endif
</div>

<div class="modal fade" id="confirmDirectorCancellationModal" tabindex="-1" aria-labelledby="confirmDirectorCancellationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDirectorCancellationModalLabel">ยืนยันการยกเลิกคำขอจากผู้อำนวยการ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                คุณแน่ใจหรือไม่ว่าต้องการยกเลิกคำขอนี้ในฐานะผู้อำนวยการ? : {{ $document->cancel_reason ?? 'n/a' }}
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                <form id="confirmDirectorCancellationForm" action="{{ route('documents.confirmDirectorCancel', ['id' => $document->document_id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="cancel_director" value="Y">
                    
                    <button type="submit" class="btn btn-danger">ยืนยันการยกเลิก</button>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection