@extends('layouts.app')


@section('content')
<!----------------------- Main Container -------------------------->
<div class="container d-flex justify-content-center align-items-center min-vh-0" style="width: 60%; max-width: 1100px;">
    <!----------------------- Login Container -------------------------->
    <div class="row border border-primary rounded-5 p-3 bg-white shadow-1 box-area" style="width: 100%; max-width: 1300px;">
        <!--------------------------- Left Box ----------------------------->
        <!-- <div class="col-md-6 d-flex justify-content-center align-items-center flex-column left-box" > style="background: #103cbe;" -->
            <!-- <div class="featured-image"> -->
                <!-- <img src="{{ asset('images/test.jpg') }}" class="img-fluid rounded-4 mb-2 mt-2" style="width: auto"> -->
                <!-- <img src="{{ asset('images/BIMS_TH.png') }}" class="img-fluid rounded-4 mb-2 mt-2" style="width: auto"> -->
            <!-- </div> -->
        <!-- </div> -->


        <div class="col-md-6 d-flex justify-content-center align-items-center flex-column left-box position-relative">
            <div class="featured-image position-relative">
        <!-- Base Image -->
            <img src="{{ asset('images/test.jpg') }}" 
                class="img-fluid rounded-4 mb-2 mt-2" 
                style="width: auto; display: block;">

        <!-- Overlay Image -->
            <img src="{{ asset('images/BIMS_TH.png') }}" 
                class="img-fluid rounded-4 mb-4 " 
                style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
            </div>
        </div>


        <!-------------------- ------ Right Box ---------------------------->
        <div class="col-md-6 right-box">
            <div class="row align-items-center">
                <div class="header-text mb-4 mt-5 text-center">
                    <h2>เข้าสู่ระบบ</h2>
                </div>
                <form method="POST" action="{{ route('login') }}">
                    @csrf


                    @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-block">
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif          


                    <div class="input-group mb-3">
                        <input id="email" type="email"
                        class="form-control form-control-lg bg-light fs-6 @error('email') is-invalid @enderror border-primary" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="อีเมล">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>


                    <div class="input-group mb-1">
                        <input id="password" type="password"
                        class="form-control form-control-lg bg-light fs-6 @error('password') is-invalid @enderror border-primary" name="password" required autocomplete="current-password" placeholder="รหัสผ่าน">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>


                    <div class="input-group mb-5 d-flex justify-content-between">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="formCheck">
                            <label for="formCheck" class="form-check-label text-secondary"><small>จำฉันไว้</small></label>
                        </div>
                        <div class="forgot">
                            <small><a href="{{ route('password.request') }}" style="color: #FB7F0D;">ลืมรหัสผ่าน?</a></small>
                        </div>
                    </div>


                    <div class="input-group mb-3">
                        <button type="submit" class="btn btn-lg btn-warning w-100 fs-6">เข้าสู่ระบบ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


