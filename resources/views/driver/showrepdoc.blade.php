@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">รายละเอียดรายงาน</h2>

    <div class="card shadow-sm">
        <div class="card-header">
            ข้อมูลการรายงานผล
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- ข้อมูลการเดินทาง -->
            <div class="mb-4">
                <h5>ข้อมูลการเดินทาง</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>เวลาออกเดินทาง:</strong> {{ $report->stime }}</p>
                        <p><strong>เวลากลับ:</strong> {{ $report->etime }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>กิโลเมตรก่อนเดินทาง:</strong> {{ $report->skilo_num }}</p>
                        <p><strong>กิโลเมตรหลังเดินทาง:</strong> {{ $report->ekilo_num }}</p>
                    </div>
                </div>
                <p><strong>จำนวนผู้ร่วมเดินทาง:</strong> {{ $report->total_companion }}</p>
            </div>

            <!-- ค่าใช้จ่าย -->
            <div class="mb-4">
                <h5>ค่าใช้จ่าย</h5>
                <p><strong>ค่าใช้จ่ายรวม:</strong> {{ number_format($report->total_cost, 2) }} บาท</p>
            </div>

            <!-- สถานะการปฏิบัติงาน -->
            <div class="mb-4">
                <h5>สถานะการปฏิบัติงาน</h5>
                <p><strong>การปฏิบัติงานเป็นไปด้วยความเรียบร้อย:</strong>
                    {{ $report->performance_isgood === 'Y' ? 'ใช่' : 'ไม่' }}
                </p>
                @if($report->performance_isgood === 'N')
                    <p><strong>เหตุผล:</strong> {{ $report->comment_issue }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection