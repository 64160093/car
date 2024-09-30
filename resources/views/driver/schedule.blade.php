@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text text-center">แผนงานในการปฏิบัติหน้าที่ของคนขับรถ</h1>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- ตรวจสอบว่ามีแผนงานหรือไม่ -->
    @if(empty($schedules))
        <div class="alert alert-info">
            {{ __('ไม่มีแผนงานสำหรับการปฏิบัติหน้าที่') }}
        </div>
    @else
        <!-- แสดงข้อมูลแผนงาน -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>วันที่</th>
                    <th>เวลาที่เริ่ม</th>
                    <th>เวลาที่สิ้นสุด</th>
                    <th>รายละเอียด</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schedules as $schedule)
                    <tr>
                        <td>{{ $schedule->date }}</td>
                        <td>{{ $schedule->start_time }}</td>
                        <td>{{ $schedule->end_time }}</td>
                        <td>{{ $schedule->details }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
