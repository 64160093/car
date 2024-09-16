@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-center">{{ __('แก้ไขข้อมูลผู้ใช้') }}</div>


                <div class="card-body">
                    <!-- แสดงข้อผิดพลาดหากมีการ validate -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        <!-- ชื่อ นามสกุล -->
                        <div class="row mb-3">
                            <label for="division_id" class="col-md-4 col-form-label text-md-end">{{ __('ชื่อ-นามสกุล') }}</label>
                            <div class="col-md-6">
                                <p id="name" class="form-control">{{ $user->name }} {{ $user->lname }}</p>
                            </div>
                        </div>

                        <!-- อีเมล -->
                        <div class="row mb-3">
                            <label for="division_id" class="col-md-4 col-form-label text-md-end">{{ __('อีเมล') }}</label>
                            <div class="col-md-6">
                                <p id="email" class="form-control">{{ $user->email }}</p>
                            </div>
                        </div>

                        <!-- เบอร์ -->
                        <div class="row mb-3">
                            <label for="division_id" class="col-md-4 col-form-label text-md-end">{{ __('เบอร์โทรศัพท์') }}</label>
                            <div class="col-md-6">
                                <p id="phonenumber" class="form-control">{{ $user->phonenumber }}</p> 
                            </div>
                        </div>

                        <!-- ส่วนงาน -->
                        <div class="row mb-3">
                            <label for="division_id" class="col-md-4 col-form-label text-md-end">{{ __('ส่วนงาน') }}</label>
                            <div class="col-md-6">
                                <select id="division_id" class="form-control @error('division_id') is-invalid @enderror" name="division_id">
                                    <option value="" disabled>{{ __('เลือกส่วนงาน') }}</option>
                                    @foreach($divisions as $division)
                                    <option value="{{ $division->division_id }}" {{ old('division_id', $user->division_id) == $division->division_id ? 'selected' : '' }}>
                                        {{ $division->division_name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('division_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- ฝ่ายงาน -->
                        <div id="department-group" style="{{ old('division_id', $user->division_id) == '2' ? 'display: block;' : 'display: none;' }}">
                            <div class="row mb-3">
                                <label for="department_id" class="col-md-4 col-form-label text-md-end">{{ __('ฝ่ายงาน') }}</label>
                                <div class="col-md-6">
                                    <select id="department_id" class="form-control @error('department_id') is-invalid @enderror" name="department_id">
                                        <option value="" disabled>{{ __('เลือกฝ่ายงาน') }}</option>
                                        @foreach($departments as $department)
                                            @if($department->division_id == 2)
                                                <option value="{{ $department->department_id }}" {{ old('department_id', $user->department_id) == $department->department_id ? 'selected' : '' }}>
                                                    {{ $department->department_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- ตำแหน่ง -->
                        <div class="row mb-3">
                            <label for="position_id" class="col-md-4 col-form-label text-md-end">{{ __('ตำแหน่งงาน') }}</label>
                            <div class="col-md-6">
                                <select id="position_id" class="form-control @error('position_id') is-invalid @enderror" name="position_id">
                                    <option value="" disabled>{{ __('เลือกตำแหน่งงาน') }}</option>
                                    @foreach($positions as $position)
                                        <option value="{{ $position->position_id }}" {{ $user->position_id == $position->position_id ? 'selected' : '' }}>
                                            {{ $position->position_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('position_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- บทบาท -->
                        <div class="row mb-3">
                            <label for="role_id" class="col-md-4 col-form-label text-md-end">{{ __('บทบาท') }}</label>
                            <div class="col-md-6">
                                <select id="role_id" class="form-control @error('role_id') is-invalid @enderror" name="role_id">
                                    <option value="" disabled>{{ __('เลือกบทบาท') }}</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->role_id }}" {{ $user->role_id == $role->role_id ? 'selected' : '' }}>
                                            {{ $role->role_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- ปุ่ม -->
                        <div class="text-center">
                            <a href="{{ route('admin.users') }}" class="btn btn-warning">ย้อนกลับ</a>
                            <button type="submit" class="btn btn-primary">อัปเดตข้อมูล</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const divisionSelect = document.getElementById('division_id');
        const departmentGroup = document.getElementById('department-group');
        const departmentSelect = document.getElementById('department_id');

        function toggleDepartmentField() {
            if (divisionSelect.value == '2') {
                departmentGroup.style.display = 'block'; // แสดง
            } else {
                departmentGroup.style.display = 'none'; // ซ่อน
                departmentSelect.value = ''; // ล้างค่า department
            }
        }

        toggleDepartmentField(); // เรียกฟังก์ชันเมื่อหน้าโหลดขึ้นมา (เพื่อตรวจสอบค่าเก่า)

        divisionSelect.addEventListener('change', toggleDepartmentField);
    });
</script>


@endsection
