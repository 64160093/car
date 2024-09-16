@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center bg-warning">{{ __('จัดการข้อมูลผู้ใช้') }}</div>

                <div class="card-body">
                    <!-- ข้อความแจ้งเตือน -->
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- ตารางข้อมูลผู้ใช้ -->
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table table-bordered">
                            <thead class="text-center">
                                <tr>
                                    <th>ชื่อ-นามสกุล</th>
                                    <th >ส่วนงาน</th>
                                    <th >ฝ่ายงาน</th>
                                    <th >ตำแหน่ง</th>
                                    <th >อีเมล</th>
                                    <th >เบอร์ติดต่อ</th>
                                    <th >Role</th>
                                    <th ></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr>
                                    <td style="white-space: nowrap;">{{ $user->name }} {{ $user->lname }}</td>
                                    <!-- ส่วนงาน -->
                                    <td style="white-space: nowrap;">
                                        @foreach($divisions as $division)
                                            @if($user->division_id == $division->division_id)
                                                {{ $division->division_name }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <!-- ฝ่ายงาน -->
                                    <td style="white-space: nowrap;">
                                        @foreach($departments as $department)
                                            @if($user->department_id == $department->department_id)
                                                {{ $department->department_name }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <!-- ตำแหน่ง -->
                                    <td style="white-space: nowrap;">
                                        @foreach($positions as $position)
                                            @if($user->position_id == $position->position_id)
                                                {{ $position->position_name }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td style="white-space: nowrap;">{{ $user->email }}</td>
                                    <td style="white-space: nowrap;">{{ $user->phonenumber }}</td>
                                    <td style="white-space: nowrap; text-align: center;"    >
                                        @foreach($roles as $role)
                                            @if($user->role_id == $role->role_id)
                                                {{ $role->role_name }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td style="white-space: nowrap;">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                                            <i class="fa fa-edit"></i> แก้ไข
                                        </a>
                                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fa fa-trash"></i> ลบ
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
