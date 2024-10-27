@extends('layouts.app')

@section('content')
<div class="container mt-5">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mb-4 shadow-sm border-1">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">{{ __('รายละเอียดรายงานที่ : ') . $report->report_id }}</h5>
        </div>
        <div class="card-body">

            <!-- ข้อมูลเจ้าหน้าที่ -->
            @if(isset($documents))
                <h6 class="text-muted">{{ __('ข้อมูลเจ้าหน้าที่') }}</h6>
                <div class="mb-3 border p-3">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ __('ข้าพระเจ้า :') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control"
                                value="{{ $documents->carmanUser->name }} {{ $documents->carmanUser->lname }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ __('หมายเลขทะเบียน :') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control"
                                value="{{ $documents->vehicle->car_category }} {{ $documents->vehicle->car_regnumber }} {{ $documents->vehicle->car_province }}"
                                readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ __('จังหวัดที่เดินทางไป :') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" value="{{ $documents->province->name_th }}" readonly>
                        </div>
                    </div>
                </div>
            @else
                <p>{{ __('ไม่พบข้อมูลเจ้าหน้าที่') }}</p>
            @endif

            <!-- ข้อมูลการเดินทาง -->
            <h6 class="text-muted">{{ __('ข้อมูลการเดินทาง') }}</h6>
            <div class="mb-3 border p-3">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>{{ __('เวลาออกเดินทาง') }}:</strong> {{ $report->stime }}</td>
                        <td><strong>{{ __('เวลากลับ') }}:</strong> {{ $report->etime }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('กิโลเมตรก่อนเดินทาง') }}:</strong> {{ $report->skilo_num }}</td>
                        <td><strong>{{ __('กิโลเมตรหลังเดินทาง') }}:</strong> {{ $report->ekilo_num }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('จำนวนผู้ร่วมเดินทาง') }}:</strong> {{ $report->total_companion }}</td>
                    </tr>
                </table>
            </div>

            <!-- ค่าใช้จ่าย -->
            <h6 class="text-muted">{{ __('รายละเอียดค่าใช้จ่าย') }}</h6>
            <div class="mb-3 border p-3">
                <ul class="list-group list-group-numbered">
                    @if(isset($report->gasoline_cost))
                        <li class="list-group-item disabled"><strong>{{ __('ค่าเชื้อเพลิง:') }}</strong>
                            {{ number_format($report->gasoline_cost, 2) . ' บาท' }}</li>
                    @endif

                    @if(isset($report->expressway_toll))
                        <li class="list-group-item disabled"><strong>{{ __('ค่าทางด่วน:') }}</strong>
                            {{ number_format($report->expressway_toll, 2) . ' บาท' }}</li>
                    @endif

                    @if(isset($report->parking_fee))
                        <li class="list-group-item disabled"><strong>{{ __('ค่าที่จอดรถ:') }}</strong>
                            {{ number_format($report->parking_fee, 2) . ' บาท' }}</li>
                    @endif

                    @if(isset($report->another_cost))
                        <li class="list-group-item disabled"><strong>{{ __('อื่น ๆ:') }}</strong>
                            {{ number_format($report->another_cost, 2) . ' บาท' }}</li>
                    @endif
                </ul>

                <p class="mt-3"><strong>{{ __('ค่าใช้จ่ายรวม:') }}</strong> <span
                        class="text-danger">{{ number_format($report->total_cost, 2) }} บาท</span></p>
            </div>

            <!-- สถานะการปฏิบัติงาน -->
            <h6 class="text-muted">{{ __('สถานะการปฏิบัติงาน') }}</h6>
            <div class="mb-3 border p-3">
                <p><strong>{{ __('การปฏิบัติงานเป็นไปด้วยความเรียบร้อย:') }}</strong>
                    <span class="text-success">{{ $report->performance_isgood === 'Y' ? 'ใช่' : 'ไม่' }}</span>
                </p>
                @if($report->performance_isgood === 'N')
                    <p><strong>{{ __('เหตุผล:') }}</strong> <span class="text-danger">{{ $report->comment_issue }}</span>
                    </p>
                @endif
            </div>



            <!-- ลายเซ็นผู้ส่ง -->
            <div class="mt-4" style="text-align: right; margin-right: 50px;">
                <p class="form-control-static"><strong>{{ __('ลายเซ็นผู้ส่ง:') }}</strong></p>
                <p class="form-control-static">{{ auth()->user()->signature }}</p>
            </div>
        </div>
    </div>
</div>
@endsection