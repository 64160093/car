@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>{{ __('หน้าแก้ไขเอกสาร') }}</h1>

    @if(!$document)
        <div class="alert alert-info">
            {{ __('ไม่มีข้อมูลเอกสาร') }}
        </div>
    @else
        <form method="POST" action="{{ route('documents.update.edit', $document->document_id) }}">
            @csrf
            @method('PUT')

            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ __('เอกสารที่: ') . $document->document_id }}</h5>
                    <p class="mb-0">
                        {{ __('วันที่ทำเรื่อง: ') . \Carbon\Carbon::parse($document->reservation_date)->format('d-m-Y') }}
                    </p>
                </div>

                <div class="card-body border p-3">
                    <!-- ข้อมูลผู้ขอ -->
                    <div class="mb-3">
                        <h6 class="text-muted">{{ __('ข้อมูลผู้ขอ') }}</h6>
                        <table class="table table-borderless border p-3">
                            <tr>
                                <td><strong>{{ __('ชื่อผู้ขอ') }}:</strong>
                                    {{ $document->reqDocumentUsers->first()->name ?? 'N/A' }}
                                    {{ $document->reqDocumentUsers->first()->lname ?? 'N/A' }}
                                </td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('ส่วนงาน') }}:</strong>
                                    {{ $document->reqDocumentUsers->first()->division->division_name ?? 'N/A' }}</td>
                                <td><strong>{{ __('ฝ่ายงาน') }}:</strong>
                                    {{ $document->reqDocumentUsers->first()->department->department_name ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- ข้อมูลการเดินทาง -->
                    <h6 class="text-muted">{{ __('ข้อมูลการเดินทาง') }}</h6>
                    <div class="mb-3 border p-3">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>{{ __('วัตถุประสงค์') }}:</strong>
                                    <input type="text" name="objective" class="form-control" value="{{ old('objective', $document->objective) }}">
                                </td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('ผู้ร่วมเดินทาง') }}:</strong>
                                    <input type="text" name="companion_name" class="form-control" value="{{ old('companion_name', $document->companion_name) }}">
                                </td>
                                <td><strong>{{ __('ผู้ร่วมเดินทางทั้งหมด') }}:</strong> {{ count(explode(',', $document->companion_name)) }} คน</td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('ผู้ควบคุมรถ') }}:</strong>
                                    {{ $document->carController->name ?? 'N/A' }} {{ $document->carController->lname ?? 'N/A' }}
                                </td>
                                <td><strong>{{ __('วันที่ไป') }}:</strong>
                                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $document->start_date) }}">
                                </td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('วันที่กลับ') }}:</strong>
                                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $document->end_date) }}">
                                </td>
                                <td><strong>{{ __('เวลาไป') }}:</strong>
                                    <input type="time" name="start_time" class="form-control" value="{{ old('start_time', $document->start_time) }}">
                                </td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('เวลากลับ') }}:</strong>
                                    <input type="time" name="end_time" class="form-control" value="{{ old('end_time', $document->end_time) }}">
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- ข้อมูลสถานที่ -->
                    <h6 class="text-muted">{{ __('ข้อมูลสถานที่') }}</h6>
                    <div class="mb-3 border p-3">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>{{ __('สถานที่') }}:</strong>
                                    <input type="text" name="location" class="form-control" value="{{ old('location', $document->location) }}">
                                </td>
                                <td><strong>{{ __('รถประเภท') }}:</strong>
                                    <input type="text" name="car_type" class="form-control" value="{{ old('car_type', $document->car_type) }}">
                                </td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('จังหวัด') }}:</strong>
                                    <input type="text" name="province" class="form-control" value="{{ old('province', $document->province->name_th) }}">
                                </td>
                                <td><strong>{{ __('อำเภอ') }}:</strong>
                                    <input type="text" name="amphoe" class="form-control" value="{{ old('amphoe', $document->amphoe->name_th) }}">
                                </td>
                                <td><strong>{{ __('ตำบล') }}:</strong>
                                    <input type="text" name="district" class="form-control" value="{{ old('district', $document->district->name_th) }}">
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- โครงการที่เกี่ยวข้อง -->
                    <h6 class="text-muted">{{ __('โครงการที่เกี่ยวข้อง') }}</h6>
                    <div class="mt-4 border p-3">
                        <p class="form-control-static">
                            @if($document->related_project)
                                <a href="{{ Storage::url($document->related_project) }}" target="_blank" class="btn btn-outline-primary">{{ __('ดูไฟล์') }}</a>
                            @else
                                {{ __('ไม่มีไฟล์') }}
                            @endif
                        </p>
                    </div>

                    <!-- ปุ่มบันทึก -->
                    <div class="mt-4" style="text-align: right;">
                        <button type="submit" class="btn btn-success">{{ __('บันทึกการแก้ไข') }}</button>
                    </div>

                </div>
            </div>
        </form>
    @endif
</div>
@endsection
