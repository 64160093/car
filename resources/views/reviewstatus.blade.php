@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mt-4">
        <div class="card-header bg-warning text-dark">
            <div class="row">
                <div class="col-md-6">
                    <strong>วันที่ไป:
                        {{ \Carbon\Carbon::parse($document->start_date)->translatedFormat('d F Y') }}</strong>
                </div>
                <div class="col-md-6 text-right">
                    <strong>วัตถุประสงค์: {{ $document->objective }}</strong>
                </div>
            </div>
        </div>
        <div class="card-body">
            <h5 class="text-center mb-4">สถานะการอนุมัติ</h5>
            <div class="row justify-content-center text-center mb-4">
                @foreach($document->reqDocumentUsers as $docUser)
                    @if ($docUser->division_id == 2)
                        <div class="col-md-2 mx-1">
                            <div class="status-card">
                                <i class="fas fa-user-circle fa-3x mb-2"></i>
                                <p>หัวหน้างาน</p>
                                <div class="badge">
                                    @if ($document->allow_department == 'approved')
                                        <span class="badge bg-success">อนุมัติ</span>
                                    @elseif ($document->allow_department == 'pending')
                                        <span class="badge bg-warning">รอดำเนินการ</span>
                                    @else
                                        <span class="badge bg-danger">ถูกปฏิเสธ</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

                <div class="col-md-2 mx-1">
                    <div class="status-card">
                        <i class="fas fa-user-circle fa-3x mb-2"></i>
                        <p>หัวหน้าฝ่าย</p>
                        <div class="badge">
                            @if ($document->allow_division == 'approved')
                                <span class="badge bg-success">อนุมัติ</span>
                            @elseif ($document->allow_division == 'pending')
                                <span class="badge bg-warning">รอดำเนินการ</span>
                            @else
                                <span class="badge bg-danger">ถูกปฏิเสธ</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-2 mx-1">
                    <div class="status-card">
                        <i class="fas fa-user-circle fa-3x mb-2"></i>
                        <p>คนสั่งรถ</p>
                        <div class="badge">
                            @if ($document->allow_opcar == 'approved')
                                <span class="badge bg-success">อนุมัติ</span>
                            @elseif ($document->allow_opcar == 'pending')
                                <span class="badge bg-warning">รอดำเนินการ</span>
                            @else
                                <span class="badge bg-danger">ถูกปฏิเสธ</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-2 mx-1">
                    <div class="status-card">
                        <i class="fas fa-user-circle fa-3x mb-2"></i>
                        <p>หัวหน้าสำนักงาน</p>
                        <div class="badge">
                            @if ($document->allow_officer == 'approved')
                                <span class="badge bg-success">อนุมัติ</span>
                            @elseif ($document->allow_officer == 'pending')
                                <span class="badge bg-warning">รอดำเนินการ</span>
                            @else
                                <span class="badge bg-danger">ถูกปฏิเสธ</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-2 mx-1">
                    <div class="status-card">
                        <i class="fas fa-user-circle fa-3x mb-2"></i>
                        <p>ผู้อำนวยการ</p>
                        <div class="badge">
                            @if ($document->allow_director == 'approved')
                                <span class="badge bg-success">อนุมัติ</span>
                            @elseif ($document->allow_director == 'pending')
                                <span class="badge bg-warning">รอดำเนินการ</span>
                            @else
                                <span class="badge bg-danger">ถูกปฏิเสธ</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mb-4">
                @if ($document->cancel_allowed == "pending")
                    <h4>สถานะปัจจุบัน:
                        <span>
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
                        </span>
                    </h4>
                @else
                    <h4>สถานะปัจจุบัน:
                        <span class="badge bg-danger">รายการคำขอถูกยกเลิกแล้ว</span>
                    </h4>
                @endif
            </div>

            <div class="text-center">
                <!-- แก้ไขเอกสาร -->
                @if ($document->cancel_allowed == "pending")
                    @if ($document->allow_carman != "pending")
                        <a href="{{ route('documents.edit', ['id' => $document->document_id]) }}"
                            class="btn btn-warning disabled">
                            {{ __('แก้ไขเอกสาร') }}
                        </a>
                    @else
                        <a href="{{ route('documents.edit', ['id' => $document->document_id]) }}" class="btn btn-warning">
                            {{ __('แก้ไขเอกสาร') }}
                        </a>
                    @endif
                @endif

                <!-- ยกเลิกคำขอ -->
                @if ($document->cancel_allowed == "pending") <!-- ยังไม่มีคำขอยกเลิก -->

                    @foreach($document->reqDocumentUsers as $docUser)  
                        <!--ฝายวิจัย-->
                        @if ($docUser->division_id == 2)
                            <!-- ไม่ผ่านการอนุมัติจากใคร -->
                            @if ($document->allow_department == "pending")
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#confirmCancelModal">
                                    ยกเลิกคำขอ
                                </button>

                                <!-- ผ่านการอนุมัติ -->
                            @else
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#reasonCancelModal">
                                    ยื่นเรื่องยกเลิกคำขอ
                                </button>
                            @endif

                        <!--ฝายอื่น-->
                        @else
                            <!-- ไม่ผ่านการอนุมัติจากใคร -->
                            @if ($document->allow_division == "pending")
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#confirmCancelModal">
                                    ยกเลิกคำขอ
                                </button>

                                <!-- ผ่านการอนุมัติ -->
                            @else
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#reasonCancelModal">
                                    ยื่นเรื่องยกเลิกคำขอ
                                </button>
                            @endif

                        @endif
                    @endforeach

                <!-- มีคำขอยกเลิก -->
                @else
                    <button type="button" class="btn btn-danger disabled" data-bs-toggle="modal"
                        data-bs-target="#confirmCancelModal">
                        ยกเลิกคำขอ
                    </button>
                @endif


                @if (auth()->user()->is_admin == 1)
                    <a href="{{ route('admin.users.form') }}" class="btn btn-secondary">ย้อนกลับ</a>
                @else
                    <a href="{{ route('documents.history') }}" class="btn btn-secondary">ย้อนกลับ</a>
                @endif
            </div>
            <div class="text-center mt-4 mb-0">
                <p style="color: red"> *** หากมีการอนุมัติคำขอใดแล้ว
                    ต้องรอการอนุมัติคำขออนุญาตยกเลิกจากผู้ที่เกี่ยวข้องก่อน</p>
            </div>
        </div>
    </div>

    @if (auth()->user()->is_admin == 1)
        @if (is_null($document->cancel_reason) || $document->cancel_reason === '')
        <!-- ไม่แสดงอะไร -->
        @else
            <div class="card mt-4">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6 pt-2">
                            <h5>คำขออนุญาตยกเลิกคำขอ</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div style="display: flex;">
                        <h5 class="mb-4 mt-3" style="margin: 0;">ต้องการยกเลิกคำขอเนื่องจาก : <u style="color: red;">{{ $document->cancel_reason }}</u></h5> 
                    </div>        
                    <div>
                        @if ( $document->cancel_admin != 'Y' )
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmCancellationModal">
                                เห็นควรให้ยกเลิกคำขอ
                            </button>
                        @else
                            <button class="btn btn-primary disabled" data-bs-toggle="modal" data-bs-target="#confirmCancellationModal">
                                เห็นควรให้ยกเลิกคำขอ
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    @endif

    <!-- Modal 1: Cancel Admin -->
    <div class="modal fade" id="confirmCancellationModal" tabindex="-1" aria-labelledby="confirmCancellationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmCancellationModalLabel">ยืนยันความคิดเห็น</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    คุณแน่ใจหรือไม่ว่าต้องยืนยันคำขอนี้?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <form id="confirmCancellationForm" action="{{ route('documents.confirmCancel', ['id' => $document->document_id]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="cancel_admin" value="Y"> <!-- กำหนดค่าเป็น Y -->
                        <button type="submit" class="btn btn-primary">ยืนยัน</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 1: Confirm Cancel Modal -->
    <div class="modal fade" id="confirmCancelModal" tabindex="-1" aria-labelledby="confirmCancelModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmCancelModalLabel">ยืนยันการยกเลิกคำขอ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    คุณแน่ใจหรือไม่ว่าต้องการยกเลิกคำขอนี้?
                </div>
                <div class="modal-footer">
                    <form action="{{ route('documents.cancel', ['id' => $document->document_id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">ยืนยันการยกเลิก</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 2: Cancel with Reason Modal -->
    <div class="modal fade" id="reasonCancelModal" tabindex="-1" aria-labelledby="reasonCancelModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reasonCancelModalLabel">ยกเลิกคำขอ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form for entering cancellation reason -->
                    <form id="cancelReasonForm"
                        action="{{ route('documents.cancel', ['id' => $document->document_id]) }}" method="POST">
                        @csrf
                        <div class="form-group mt-3">
                            <label for="cancel_reason">เหตุผลในการยกเลิก</label>
                            <textarea name="cancel_reason" id="cancel_reason" class="form-control" rows="3"
                                required></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                            <button type="submit" class="btn btn-danger">ยืนยันการยกเลิก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Optional: Validate that the reason is filled
        document.getElementById('cancelReasonForm').addEventListener('submit', function (event) {
            const cancelReason = document.getElementById('cancel_reason').value.trim();
            if (!cancelReason) {
                alert('กรุณากรอกเหตุผลในการยกเลิกคำขอ');
                event.preventDefault();
            }
        });
    </script>


    <style>
        .status-card {
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 10px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .status-card:hover {
            transform: translateY(-5px);
        }
    </style>
    @endsection