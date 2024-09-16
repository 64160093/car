@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-center">เพิ่มพาหนะ</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('store.vehicle') }}">
                        @csrf
                        <!-- ประเภทรถ -->
                        <div class="row mb-3">
                            <label for="icon_id" class="col-md-4 col-form-label text-md-end">{{ __('ประเภทพาหนะ') }}</label>
                            <div class="col-md-6">
                                <select id="icon_id" class="form-control @error('icon_id') is-invalid @enderror" name="icon_id" required>
                                    <option value="" disabled selected>{{ __('เลือกประเภทพาหนะ') }}</option>
                                    @foreach($car_icons as $car_icon)
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
                            <a href="{{ route('show.vehicles') }}" class="btn btn-warning">ย้อนกลับ</a>
                            <button type="submit" class="btn btn-primary">ยืนยัน</button>

                            @if ($errors->any())
                                <div class="alert alert-danger mt-3">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
