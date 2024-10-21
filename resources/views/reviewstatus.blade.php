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
                <h4>สถานะปัจจุบัน:</h4>
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
            </div>

            <div class="text-center">
                <!-- <a href="#" class="btn btn-warning">แก้ไขแบบฟอร์มเพิ่มเติม</a> -->
                <a href="{{ route('documents.edit', ['id' => $document->document_id]) }}" class="btn btn-warning">
    {{ __('แก้ไขเอกสาร') }}
</a>


                <a href="#" class="btn btn-danger">ต้องการยกเลิกคำขอ</a>
                @if (auth()->user()->is_admin == 1)
                    <a href="{{ route('admin.users.form') }}" class="btn btn-secondary">ย้อนกลับ</a>
                @else
                    <a href="{{ route('documents.history') }}" class="btn btn-secondary">ย้อนกลับ</a>
                @endif
            </div>
        </div>
    </div>
</div>

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