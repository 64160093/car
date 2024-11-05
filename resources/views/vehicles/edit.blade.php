@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('แก้ไขข้อมูลพาหนะ') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('vehicles.update', $vehicle->car_id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="icon_id" class="col-md-4 col-form-label text-md-end">{{ __('ประเภทพาหนะ') }}</label>
                            <div class="col-md-6">
                                <select id="icon_id" class="form-control @error('icon_id') is-invalid @enderror" name="icon_id" required>
                                    @foreach($car_icons as $car_icon)
                                        <option value="{{ $car_icon->icon_id }}" {{ $vehicle->icon_id == $car_icon->icon_id ? 'selected' : '' }}>
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

                        <div class="row mb-3">
                            <label for="car_category" class="col-md-4 col-form-label text-md-end">{{ __('หมวดทะเบียน') }}</label>
                            <div class="col-md-6">
                                <input id="car_category" type="text" class="form-control @error('car_category') is-invalid @enderror" name="car_category" value="{{ $vehicle->car_category }}" required>
                                @error('car_category')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="car_regnumber" class="col-md-4 col-form-label text-md-end">{{ __('เลขทะเบียน') }}</label>
                            <div class="col-md-6">
                                <input id="car_regnumber" type="text" class="form-control @error('car_regnumber') is-invalid @enderror" name="car_regnumber" value="{{ $vehicle->car_regnumber }}" required>
                                @error('car_regnumber')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="car_province" class="col-md-4 col-form-label text-md-end">{{ __('จังหวัด') }}</label>
                            <div class="col-md-6">
                                <input id="car_province" type="text" class="form-control @error('car_province') is-invalid @enderror" name="car_province" value="{{ $vehicle->car_province }}" required>
                                @error('car_province')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mt-4 text-center">
                            <button type="submit" class="btn btn-primary">{{ __('บันทึกการเปลี่ยนแปลง') }}</button>
                            <a href="{{ route('show.vehicles') }}" class="btn btn-secondary">{{ __('ยกเลิก') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
