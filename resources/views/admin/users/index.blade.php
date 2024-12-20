@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<div class="container">
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header text-center bg-warning">{{ __('จัดการข้อมูลผู้ใช้') }}</div>

            <div class="card-body">
                <!-- ข้อความแจ้งเตือน -->
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


                <!-- ช่องค้นหาชื่อ-นามสกุล -->
                <div class="container-fluid mt-2">
                    <form class="d-flex" method="GET" action="{{ route('admin.users.search') }}">
                        <input type="search" id="searchName" name="q" class="form-control me-2" 
                            placeholder="ค้นหารายชื่อ อีเมล" aria-label="Search" value="{{ request()->get('q') }}">
                        <button type="submit" class="btn btn-primary">ค้นหา</button>
                        <a href="{{ route('register') }}" class="btn btn btn-primary ms-2">
                                <i class="bi bi-plus-circle"></i>
                        </a>
                    </form>


                    <!-- ตารางข้อมูลผู้ใช้ -->
                    <div class="table-responsive mt-4 mb-4" style="overflow-x: auto;">
                        <table class="table table-bordered">
                            <thead class="text-center">
                                <tr>
                                    <th style="white-space: nowrap;">ชื่อ-นามสกุล</th>
                                    <th style="white-space: nowrap;">ส่วนงาน</th>
                                    <th style="white-space: nowrap;">ฝ่ายงาน</th>
                                    <th style="white-space: nowrap;">ตำแหน่ง</th>
                                    <th style="white-space: nowrap;">อีเมล</th>
                                    <th style="white-space: nowrap;">เบอร์ติดต่อ</th>
                                    <th style="white-space: nowrap;">Role</th>
                                    <th style="white-space: nowrap;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td style="white-space: nowrap;">{{ $user->name }} {{ $user->lname }}
                                        </td>
                                        <!-- ส่วนงาน -->
                                        <td style="white-space: nowrap;">
                                            @foreach ($divisions as $division)
                                                @if ($user->division_id == $division->division_id)
                                                    {{ $division->division_name }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <!-- ฝ่ายงาน -->
                                        <td style="white-space: nowrap;">
                                            @foreach ($departments as $department)
                                                @if ($user->department_id == $department->department_id)
                                                    {{ $department->department_name }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <!-- ตำแหน่ง -->
                                        <td style="white-space: nowrap;">
                                            @foreach ($positions as $position)
                                                @if ($user->position_id == $position->position_id)
                                                    {{ $position->position_name }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td style="white-space: nowrap;">{{ $user->email }}</td>
                                        <td style="white-space: nowrap;">{{ $user->phonenumber }}</td>
                                        <td style="white-space: nowrap; text-align: center;">
                                            @foreach ($roles as $role)
                                                @if ($user->role_id == $role->role_id)
                                                    {{ $role->role_name }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td style="white-space: nowrap;">
                                            <a href="{{ route('admin.users.edit', Crypt::encryptString($user->id)) }}"
                                                class="btn btn-primary">
                                                <i class="fa fa-edit"></i> แก้ไข
                                            </a>
                                            <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.delete', $user->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $user->id }})">
                                                    <i class="fa fa-trash"></i> ลบ
                                                </button>
                                            </form>

                                            <script>
                                                function confirmDelete(userId) {
                                                    Swal.fire({
                                                        title: 'คุณแน่ใจหรือไม่?',
                                                        text: "ข้อมูลนี้จะถูกลบและไม่สามารถกู้คืนได้!",
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#d33',
                                                        cancelButtonColor: '#3085d6',
                                                        confirmButtonText: 'ลบ',
                                                        cancelButtonText: 'ยกเลิก'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            document.getElementById(`delete-form-${userId}`).submit();
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
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
