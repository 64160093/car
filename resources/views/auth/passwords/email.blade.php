@extends('layouts.app')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border border-primary rounded-4 p-3 bg-white shadow-1 box-area" >
                <!-- <div class="card-header">{{ __('ลืมรหัสผ่าน') }}</div> -->
                 <h3 class="text-center mb-4 mt-3">ลืมรหัสผ่าน</h3>

                <div class="card-body">
                @if (session('status'))
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ',
                            text: '{{ session('status') }}',
                            timer: 2000, // ปิดหลังจาก 5 วินาที
                            timerProgressBar: true,
                            willClose: () => {
                                // ทำงานบางอย่างเมื่อ popup ปิด
                            }
                        });
                    </script>
                @endif


                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('อีเมล') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror border-primary" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-warning">
                                    {{ __('ส่งข้อมูล') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
