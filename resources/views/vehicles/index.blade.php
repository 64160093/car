@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center bg-warning">{{ __('รายการรถทั้งหมด') }}</div>

                <div class="card-body">
                @if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'สำเร็จ',
            text: '{{ session('success') }}',
            confirmButtonText: 'ตกลง'
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด',
            text: '{{ session('error') }}',
            confirmButtonText: 'ตกลง'
        });
    </script>
@endif


                    <!-- ตารางข้อมูลรถในกรอบ -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="text-center">
                                <tr>
                                    <th style="width: 10%;">ประเภท</th>
                                    <th style="width: 65%;">หมายเลขทะเบียน</th>
                                    <th style="width: 15%;">สถานะ</th>
                                    <th style="width: 10%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($vehicles as $vehicle)
                                <tr>
                                    <!-- icon -->
                                    <td style="text-align: center;">
                                        @foreach ($car_icons as $car_icon)
                                            @if ($vehicle->icon_id == $car_icon->icon_id)
                                                <img src="{{ asset('images/' . $car_icon->icon_img) }}" alt="Icon Image" width="45" height="45">
                                            @endif
                                        @endforeach
                                    </td>

                                    <!-- เลขทะเบียนกับสีพื้นหลัง -->
                                    <td style="vertical-align: middle; background-color: 
                                        @foreach ($car_icons as $car_icon)
                                            @if ($vehicle->icon_id == $car_icon->icon_id)
                                                {{ $car_icon->icon_color }};
                                            @endif
                                        @endforeach">
                                        {{ $vehicle->car_category }} {{ $vehicle->car_regnumber }} {{ $vehicle->car_province }}
                                    </td>

                                    <!-- สถานะ -->
                                    <td style="text-align: center; vertical-align: middle;">
                                        <form action="{{ route('vehicles.updateStatus', $vehicle->car_id) }}" method="POST">
                                            @csrf
                                            <label style="color: {{ $vehicle->car_status == 'Y' ? 'green' : 'gray' }}">
                                                <input type="radio" name="car_status_{{ $vehicle->car_id }}" value="Y" onchange="this.form.submit()" {{ $vehicle->car_status == 'Y' ? 'checked' : '' }}> พร้อม
                                            </label>
                                            <label style="color: {{ $vehicle->car_status == 'N' ? 'red' : 'gray' }}">
                                                <input type="radio" name="car_status_{{ $vehicle->car_id }}" value="N" onchange="document.getElementById('reason_{{ $vehicle->car_id }}').style.display='block'; this.form.submit()" {{ $vehicle->car_status == 'N' ? 'checked' : '' }}> ไม่พร้อม
                                            </label>

                                            <div id="reason_{{ $vehicle->car_id }}" style="display: {{ $vehicle->car_status == 'N' ? 'block' : 'none' }};">
                                                <input type="text" name="car_reason_{{ $vehicle->car_id }}" placeholder="กรุณาระบุเหตุผล" value="{{ $vehicle->car_reason }}">
                                            </div>
                                        </form>
                                    </td>
                                    
                                    <!-- ลบข้อมูล -->
                                    <td style="text-align: center;">
                                        <form id="delete-form-{{ $vehicle->car_id }}" action="{{ route('vehicles.destroy', $vehicle->car_id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $vehicle->car_id }})">
                                                <i class="fa fa-trash"></i> ลบ
                                            </button>
                                        </form>
                                        <script>
    function confirmDelete(carId) {
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "คุณจะไม่สามารถย้อนกลับได้!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + carId).submit();
            }
        });
    }
</script>


                                        
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- เพิ่มรถ -->
                <div class="text-center">
                    <button type="submit" class="btn btn-warning mb-4" data-bs-toggle="modal" data-bs-target="#addVehicleModal">เพิ่มรถ</button>
                </div>
            </div>
        </div>
    </div>
</div>



    <!-- Modal -->
    <div class="modal fade" id="addVehicleModal" tabindex="-1" aria-labelledby="addVehicleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-center">
                    <h5 class="modal-title text-center" id="addVehicleModalLabel">เพิ่มพาหนะ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <!-- ฟอร์มการเพิ่มพาหนะ -->
                    <form method="POST" action="{{ route('store.vehicle') }}">
                        @csrf
                        <!-- ประเภทรถ -->
                        <div class="row mb-3">
                            <label for="icon_id" class="col-md-4 col-form-label text-md-end">{{ __('ประเภทพาหนะ') }}</label>
                            <div class="col-md-6">
                                <select id="icon_id" class="form-control @error('icon_id') is-invalid @enderror" name="icon_id" required>
                                    <option value="" disabled selected>{{ __('เลือกประเภทพาหนะ') }}</option>
                                    @foreach($availableCarIcons as $car_icon)
                                        <option value="{{ $car_icon->icon_id }}" {{ old('icon_id') == $car_icon->icon_id ? 'selected' : '' }}>
                                            {{ $car_icon->type_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('icon_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- หมวดเลขทะเบียน -->
                        <div class="row mb-3">
                            <label for="car_category" class="col-md-4 col-form-label text-md-end">{{ __('หมวดทะเบียน') }}</label>
                            <div class="col-md-6">
                                <input id="car_category" type="text" class="form-control @error('car_category') is-invalid @enderror" name="car_category" value="{{ old('car_category') }}" required autocomplete="car_category">
                                @error('car_category')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- เลขทะเบียน -->
                        <div class="row mb-3">
                            <label for="car_regnumber" class="col-md-4 col-form-label text-md-end">{{ __('เลขทะเบียน') }}</label>
                            <div class="col-md-6">
                                <input id="car_regnumber" type="text" class="form-control @error('car_regnumber') is-invalid @enderror" name="car_regnumber" value="{{ old('car_regnumber') }}" required autocomplete="car_regnumber">
                                @error('car_regnumber')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- จังหวัดของรถ -->
                        <div class="row mb-3">
                            <label for="car_province" class="col-md-4 col-form-label text-md-end">{{ __('จังหวัด') }}</label>
                            <div class="col-md-6">
                                <input id="car_province" type="text" class="form-control @error('car_province') is-invalid @enderror" name="car_province" value="{{ old('car_province') }}" required autocomplete="car_province">
                                @error('car_province')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- ปุ่ม -->
                        <div class="form-group mt-4 text-center">
                            <button type="submit" class="btn btn-primary">ยืนยัน</button>

                            <!-- @if ($errors->any())
                                <div class="alert alert-danger mt-3">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>   
    @section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            @if ($errors->any())
                $('#addVehicleModal').modal('show');
            @endif
        });
    </script>
    @endsection
@endsection

    
