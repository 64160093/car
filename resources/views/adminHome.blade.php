@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                    ur admin
                </div>
            </div>

            <form action="{{ url('/vehicles') }}" method="get">
                <button type="submit" class="btn btn-primary mt-3">แสดงข้อมูลรถ</button>
            </form>

            <form action="{{ url('/admin/users') }}" method="get">
                <button type="submit" class="btn btn-primary mt-3">จัดการข้อมูลผู้ใช้</button>
            </form>
            
        </div>
    </div>
</div>
@endsection
