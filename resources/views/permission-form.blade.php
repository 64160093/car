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
                            {{ \Carbon\Carbon::parse($document->start_date)->locale('th')->translatedFormat('F')}} พ.ศ. 
                            {{ \Carbon\Carbon::parse($document->start_date)->format('Y') + 543 }}<br>
                            เวลา : {{ \Carbon\Carbon::parse($document->start_time)->format('H:i') }} น.
                        </td>
                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($document->end_date)->format('d') }}
                            {{ \Carbon\Carbon::parse($document->end_date)->locale('th')->translatedFormat('F') }} พ.ศ. 
                            {{ \Carbon\Carbon::parse($document->end_date)->format('Y') + 543 }}<br>
                            เวลา : {{ \Carbon\Carbon::parse($document->end_time)->format('H:i') }} น.
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
                            <a href="{{ route('documents.show') }}?id={{ $document->document_id }}" class="btn 
                                @if (($document->allow_director != 'approved' && $document->cancel_admin == 'Y') || 
                                        ($document->allow_director == 'approved' && $document->cancel_admin == 'Y' && $document->cancel_director == 'Y'))
                                    btn-secondary
                                @else
                                    btn-primary
                                @endif"> ดูรายละเอียด
                            </a>

                            @if ( in_array(auth()->user()->role_id, [3]))
                                @if ( $document->cancel_admin == 'Y' && $document->cancel_director != 'Y' )
                                    <button class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#confirmDirectorCancellationModal"
                                        data-document-id="{{ $document->document_id }}"
                                        data-cancel-reason="{{ $document->cancel_reason }}">
                                        !
                                    </button>
                                @endif

                                @if ( $document->edit_by == 1 )
                                    <button class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#viewEditAllowedModal"
                                        data-edit-allowed="{{ $document->edit_allowed }}">
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



<!-- Modal แสดงข้อมูล edit_allowed -->
<div class="modal fade" id="viewEditAllowedModal" tabindex="-1"
    aria-labelledby="viewEditAllowedModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewEditAllowedModalLabel">มีการแก้ไขข้อมูลคำขอ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <strong>ต้องการแก้ไขเนื่องจาก : </strong><span id="editAllowedText"></span><br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

<!-- Model edit dirceter -->
<div class="modal fade" id="confirmDirectorCancellationModal" tabindex="-1"
    aria-labelledby="confirmDirectorCancellationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDirectorCancellationModalLabel">ยืนยันคำขอยกเลิก
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <strong>เหตุผลการยกเลิก: </strong><span id="cancelReasonText"></span><br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                <form id="confirmDirectorCancellationForm"
                    action="{{ route('documents.confirmDirectorCancel', ['id' => $document->document_id]) }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="cancel_director" value="Y">
                    <input type="hidden" name="document_id" id="documentIdInput">

                    <button type="submit" class="btn btn-danger">ยืนยันการยกเลิก</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    //ยื่นยันคำขอยกเลิก
    var myModal = document.getElementById('confirmDirectorCancellationModal');
    myModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // ปุ่มที่เปิด modal
        var cancelReason = button.getAttribute('data-cancel-reason'); // ดึงข้อมูลเหตุผลการยกเลิก
        var cancelReasonText = myModal.querySelector('#cancelReasonText'); // ค้นหาส่วนที่จะแสดงเหตุผล
        var documentIdInput = myModal.querySelector('#documentIdInput'); // ค้นหา input ที่ซ่อน
        // ตั้งค่าข้อความเหตุผลการยกเลิก
        cancelReasonText.textContent = cancelReason ? cancelReason : 'ไม่มีข้อมูลเหตุผล';
        var documentId = button.getAttribute('data-document-id'); // ดึง document_id
        documentIdInput.value = documentId; // ตั้งค่า document_id
    });

    // แสดงข้อมูลที่แก้ไข
    var editAllowedModal = document.getElementById('viewEditAllowedModal');
    editAllowedModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // ปุ่มที่เปิด modal
        var editAllowed = button.getAttribute('data-edit-allowed'); // ดึงข้อมูล edit_allowed
        var editAllowedText = editAllowedModal.querySelector('#editAllowedText'); // ค้นหาส่วนที่จะแสดงข้อมูล
        // ตั้งค่าข้อความข้อมูล edit_allowed
        editAllowedText.textContent = editAllowed ? editAllowed : 'ไม่มีข้อมูล';
    });
</script>


@endsection