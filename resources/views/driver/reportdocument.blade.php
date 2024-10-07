@extends('layouts.app')

@section('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Report Document</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
@endsection

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">รายงานผลปฏิบัติงาน</h2>
    
    <div class="d-flex justify-content-end mb-3">
        <form action="{{ route('report.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <button type="submit" class="btn btn-warning">ส่ง</button>
        </form>
    </div>

    <div class="card shadow-sm">
        <form action="{{ route('report.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Officer Information and Trip Information in a two-column layout -->
            <div class="row mb-4">
                <!-- Officer Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            ข้อมูลเจ้าหน้าที่
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="officer_name" class="col-sm-3 col-form-label text-right">ข้าพระเจ้า :</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="officer_name" name="officer_name"
                                        value="นายสมควร แสงบุญ" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="num_people" class="col-sm-3 col-form-label text-right">ผู้ร่วมเดินทาง จำนวน
                                    :</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="num_people" name="num_people" value="5"
                                        readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="registration" class="col-sm-3 col-form-label text-right">หมายเลขทะเบียน
                                    :</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="registration" name="registration"
                                        value="กม 1234 ชลบุรี" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="province" class="col-sm-3 col-form-label text-right">จังหวัด :</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="province" name="province" value="ชลบุรี"
                                        readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trip Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            ข้อมูลการเดินทาง
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="before_trip" class="col-sm-5 col-form-label text-right">หมายเลขไมล์ก่อนเดินทาง
                                    :</label>
                                <div class="col-sm-7">
                                    <input type="number" class="form-control" id="before_trip" name="before_trip">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="after_trip" class="col-sm-5 col-form-label text-right">หมายเลขไมล์หลังเดินทาง
                                    :</label>
                                <div class="col-sm-7">
                                    <input type="number" class="form-control" id="after_trip" name="after_trip">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="start_time" class="col-sm-5 col-form-label text-right">กลับถึงสถาบันวิทยาศาสตร์
                                    เวลา :</label>
                                <div class="col-sm-7">
                                    <input type="time" class="form-control" id="start_time" name="start_time">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expenses -->
            <div class="card mb-4">
                <div class="card-header">
                    ค่าใช้จ่ายที่ต้องออกตามรายการ
                </div>
                <div class="card-body">
                    @foreach(['ค่าที่พัก', 'ค่าอาหาร', 'ค่าเชื้อเพลิง', 'อื่น ๆ'] as $key => $expense)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="option{{ $key + 1 }}" name="expenses[]"
                                value="{{ $expense }}">
                            <label class="form-check-label" for="option{{ $key + 1 }}">{{ $expense }}</label>
                            <input type="number" class="form-control mt-2" id="amount{{ $key + 1 }}" name="amount{{ $key + 1 }}"
                                placeholder="จำนวนเงิน">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Task Completion and Remarks in a two-column layout -->
            <div class="card mb-4">
                <!-- Task Completion -->
                <div class="card-header">
                    การปฏิบัติงาน
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">การปฏิบัติงานเป็นไปด้วยความเรียบร้อย :</label>

                        <div class="col-sm-9">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="success_yes" name="success" value="yes">
                                <label class="form-check-label" for="success_yes">ใช่</label>
                            </div>

                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" id="success_no" name="success" value="no">
                                <label for="remarks" class="col-sm-3 col-form-label text-right">อื่น ๆ :</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Signature -->
            <div class="card mb-4">

                <div class="card-body">
                    <div class="form-group row">
                        <label for="signature" class="col-sm-3 col-form-label text-right">ลายเซ็นผู้ส่ง :</label>
                        <div class="col-sm-9">
                            <input type="file" class="form-control" id="signature" name="signature">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
