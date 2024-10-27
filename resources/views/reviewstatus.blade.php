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
                @if ( $document->cancel_allowed == "pending")
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
                    </span></h4>
                @else
                    <h4>สถานะปัจจุบัน:
                        <span class="badge bg-danger">รายการคำขอถูกยกเลิกแล้ว</span>
                    </h4>
                @endif
            </div>

            <div class="text-center">
                <!-- แก้ไขเอกสาร -->
                @if ( $document->cancel_allowed == "pending")
                    @if ($document->allow_carman != "pending")
                        <a href="{{ route('documents.edit', ['id' => $document->document_id]) }}" class="btn btn-warning disabled">
                            {{ __('แก้ไขเอกสาร') }}
                        </a>
                    @else
                        <a href="{{ route('documents.edit', ['id' => $document->document_id]) }}" class="btn btn-warning">
                            {{ __('แก้ไขเอกสาร') }}
                        </a>
                    @endif
                @endif

                <!-- ยกเลิกคำขอ -->
                @if ( $document->cancel_allowed == "pending")
                    @if ($document->allow_carman != "pending")
                        <a href="#" class="btn btn-danger disabled" data-bs-toggle="modal" data-bs-target="#cancelRequestModal">
                        ต้องการยกเลิกคำขอ</a>
                    @else
                        <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelRequestModal">
                        ต้องการยกเลิกคำขอ</a>
                    @endif
                @endif
                

                @if (auth()->user()->is_admin == 1)
                    <a href="{{ route('admin.users.form') }}" class="btn btn-secondary">ย้อนกลับ</a>
                @else
                    <a href="{{ route('documents.history') }}" class="btn btn-secondary">ย้อนกลับ</a>
                @endif
            </div>
        </div>
    </div>
</div>


<!-- Modal สำหรับยืนยันการยกเลิก -->
<div class="modal fade" id="cancelRequestModal" tabindex="-1" aria-labelledby="cancelRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelRequestModalLabel">ยืนยันการยกเลิกคำขอ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                คุณแน่ใจหรือไม่ว่าต้องการยกเลิกคำขอนี้?

                <!-- ฟอร์มสำหรับกรอกเหตุผล -->
                <form id="cancelForm" action="{{ route('documents.cancel', ['id' => $document->document_id]) }}" method="POST">
                    @csrf
                    <div class="form-group mt-3">
                        <label for="cancel_reason">เหตุผลในการยกเลิก</label>
                        <textarea name="cancel_reason" id="cancel_reason" class="form-control" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                <button type="submit" class="btn btn-danger" form="cancelForm">ยืนยันการยกเลิก</button>
            </div>
        </div>
    </div>
</div>


<script>
    document.getElementById('cancelForm').addEventListener('submit', function(event) {
    // ดึงค่าเหตุผลจาก textarea
    const cancelReason = document.getElementById('cancel_reason').value.trim();

    // ตรวจสอบว่ากรอกเหตุผลหรือไม่
    if (!cancelReason) {
        // ถ้าไม่ได้กรอก แสดงแจ้งเตือนและป้องกันการส่งฟอร์ม
        alert('กรุณากรอกเหตุผลในการยกเลิกคำขอ');
        event.preventDefault(); // ป้องกันการส่งฟอร์ม
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