@extends('layouts.pdf')  <!-- ใช้ layout สำหรับ PDF -->

@section('content')
<div style="font-family: Arial, sans-serif; margin: 20px;">
    <h5 style="text-align: center;">รายละเอียดรายงานที่ : {{ $report->report_id }}</h5>

    <!-- ข้อมูลเจ้าหน้าที่ -->
    @if(isset($documents))
        <h6>ข้อมูลเจ้าหน้าที่</h6>
        <p>ข้าพระเจ้า : {{ $documents->carmanUser->name }} {{ $documents->carmanUser->lname }}</p>
        <p>หมายเลขทะเบียน : {{ $documents->vehicle->car_category }} {{ $documents->vehicle->car_regnumber }}
            {{ $documents->vehicle->car_province }}</p>
        <p>จังหวัดที่เดินทางไป : {{ $documents->province->name_th }}</p>
    @else
        <p>ไม่พบข้อมูลเจ้าหน้าที่</p>
    @endif

    <!-- ข้อมูลการเดินทาง -->
    <h6>ข้อมูลการเดินทาง</h6>
    <p>เวลาออกเดินทาง: {{ $report->stime }}</p>
    <p>เวลากลับ: {{ $report->etime }}</p>
    <p>กิโลเมตรก่อนเดินทาง: {{ $report->skilo_num }}</p>
    <p>กิโลเมตรหลังเดินทาง: {{ $report->ekilo_num }}</p>
    <p>จำนวนผู้ร่วมเดินทาง: {{ $report->total_companion }}</p>

    <!-- ค่าใช้จ่าย -->
    <h6>รายละเอียดค่าใช้จ่าย</h6>
    <p>ค่าที่พัก: {{ number_format($report->gasoline_cost, 2) }} บาท</p>
    <p>ค่าอาหาร: {{ number_format($report->expressway_toll, 2) }} บาท</p>
    <p>ค่าเชื้อเพลิง: {{ number_format($report->parking_fee, 2) }} บาท</p>
    <p>อื่น ๆ: {{ number_format($report->another_cost, 2) }} บาท</p>
    <p>ค่าใช้จ่ายรวม: {{ number_format($report->total_cost, 2) }} บาท</p>

    <!-- สถานะการปฏิบัติงาน -->
    <h6>สถานะการปฏิบัติงาน</h6>
    <p>การปฏิบัติงานเป็นไปด้วยความเรียบร้อย: {{ $report->performance_isgood === 'Y' ? 'ใช่' : 'ไม่' }}</p>
    @if($report->performance_isgood === 'N')
        <p>เหตุผล: {{ $report->comment_issue }}</p>
    @endif

    <!-- ลายเซ็นผู้ส่ง -->
    <p>ลายเซ็นผู้ส่ง: {{ auth()->user()->signature }}</p>
</div>
@endsection