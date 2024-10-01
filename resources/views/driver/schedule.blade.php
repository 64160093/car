@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">แผนงานในการปฏิบัติหน้าที่ของคนขับรถ</h1>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(empty($schedules))
        <div class="alert alert-info">
            {{ __('ไม่มีแผนงานสำหรับการปฏิบัติหน้าที่') }}
        </div>
    @else
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">วันที่</th>
                    <th class="text-center">เวลาที่เริ่ม</th>
                    <th class="text-center">เวลาที่สิ้นสุด</th>
                    <th class="text-center">รายละเอียด</th>
                    <th class="text-center">สถานะ</th>
                    <th class="text-center">การกระทำ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schedules as $schedule)
                    <tr>
                        <td class="text-center">{{ $schedule->id }}</td>
                        <td class="text-center">{{ $schedule->date }}</td>
                        <td class="text-center">{{ $schedule->start_time }}</td>
                        <td class="text-center">{{ $schedule->end_time }}</td>
                        <td class="text-center">{{ $schedule->details }}</td>
                        <td class="text-center">
                            @if($schedule->status == 'approved')
                                <span class="badge bg-success">อนุมัติ</span>
                            @elseif($schedule->status == 'pending')
                                <span class="badge bg-warning">รอดำเนินการ</span>
                            @else
                                <span class="badge bg-danger">ถูกปฏิเสธ</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('schedule.show', ['id' => $schedule->id]) }}" class="btn btn-primary">ดูรายละเอียด</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
