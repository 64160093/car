@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-center">{{ __('แก้ไขข้อมูลส่วนตัว') }}</div>

                <div class="container mt-2">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- ชื่อ -->
                        <div class="mb-3">
                            <label for="name" class="form-label mt-2">ชื่อ</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}">
                        </div>

                        <!-- นามสกุล -->
                        <div class="mb-3">
                            <label for="lname" class="form-label mt-2">นามสกุล</label>
                            <input type="text" name="lname" id="lname" class="form-control" value="{{ $user->lname }}">
                        </div>

                        <!-- อีเมล -->
                        <div class="mb-3">
                            <label for="email" class="form-label">อีเมล</label>
                            <p id="email" class="form-control">{{ $user->email }}</p>
                        </div>

                        <!-- เบอร์ -->
                        <div class="mb-3">
                            <label for="phonenumber" class="form-label">เบอร์โทรศัพท์</label>
                            <input type="text" name="phonenumber" id="phonenumber" class="form-control" value="{{ $user->phonenumber }}">
                            @error('phonenumber')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- ลายเซ็น -->
                        <div class="mb-3">
                            <label for="signature_name" class="form-label">รูปภาพลายเซ็น (.png ขนาด 530x120 px)</label>
                            <input type="file" name="signature_name" id="signature_name" class="form-control">
                            @error('signature_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <!-- @if ($user->signature_name)
                                <img src="{{ asset('storage/' . $user->signature_name) }}" alt="Signature Image" class="img-fluid mt-2">
                            @endif -->
                            @if ($user->signature_name)
                                <img src="{{ url('/signatures/' . basename($user->signature_name)) }}" alt="Signature Image" class="img-fluid mt-2">
                            @endif
                        </div>

                        <!-- ส่วนงาน -->
                        <div class="mb-3">
                            <label for="division" class="form-label">ส่วนงาน</label>
                            <p id="division" class="form-control @error('division') is-invalid @enderror">
                                @foreach($divisions as $division)
                                    @if($user->division_id == $division->division_id)
                                        {{ $division->division_name }}
                                    @endif
                                @endforeach
                            </p>
                            @error('division')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- ฝ่ายงาน -->
                        @if($user->department_id != null)
                        <div class="mb-3">
                            <label for="department" class="form-label">ฝ่ายงาน</label>
                            <p id="department" class="form-control @error('department_id') is-invalid @enderror">
                                @foreach($departments as $department)
                                    @if($user->department_id == $department->department_id)
                                        {{ $department->department_name }}
                                    @endif
                                @endforeach
                            </p>
                            @error('department_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @endif

                        <!-- ตำแหน่งงาน -->
                        <div class="mb-3">
                            <label for="position" class="form-label">ตำแหน่ง</label>
                            <p id="position" class="form-control @error('position_id') is-invalid @enderror">
                                @foreach($positions as $position)
                                    @if($user->position_id == $position->position_id)
                                        {{ $position->position_name }}
                                    @endif
                                @endforeach
                            </p>
                            @error('position_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mt-4 text-center mb-3"> 
                            @if (auth()->user()->is_admin == 1)
                                <a href="{{ route('admin.home') }}" class="btn btn-warning">ย้อนกลับ</a>
                            @else
                                <a href="{{ route('home') }}" class="btn btn-warning">ย้อนกลับ</a>
                            @endif
                            <button type="submit" class="btn btn-primary">อัปเดตข้อมูล</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
