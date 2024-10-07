@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Permission Form</h1>

    @if($documents->isEmpty())
        <div class="alert alert-info">
            {{ __('ไม่มีฟอร์มสำหรับการตรวจสอบ') }}
        </div>
    @else
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">ผู้ส่ง</th>
                    <th class="text-center">วัตถุประสงค์</th>
                    <th class="text-center">วันที่สร้าง</th>
                    <th class="text-center">สถานะ</th>
                    <th class="text-center">การกระทำ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $document)
                    <tr>
                        <td class="text-center">{{ $document->document_id }}</td>
                        <td class="text-center">
                            @foreach($document->reqDocumentUsers as $reqDocumentUser)
                                {{ $reqDocumentUser->user->name }} {{ $reqDocumentUser->user->lname }}<br>
                            @endforeach
                        </td>
                        <td class="text-center">{{ $document->objective }}</td>
                        <td class="text-center">{{ $document->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-center">
                            @if (in_array(auth()->user()->role_id, [4, 5, 6, 7, 8, 9, 10]))
                                @if ($document->allow_division == 'approved')
                                    <span class="badge bg-success">อนุมัติ</span>
                                @elseif ($document->allow_division == 'pending')
                                    <span class="badge bg-warning">รอดำเนินการ</span>
                                @else
                                    <span class="badge bg-danger">ถูกปฏิเสธ</span>
                                @endif
                            @elseif (in_array(auth()->user()->role_id, [13, 14, 15, 16]))
                                @if ($document->allow_department == 'approved')
                                    <span class="badge bg-success">อนุมัติ</span>
                                @elseif ($document->allow_department == 'pending')
                                    <span class="badge bg-warning">รอดำเนินการ</span>
                                @else
                                    <span class="badge bg-danger">ถูกปฏิเสธ</span>
                                @endif
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('documents.show') }}?id={{ $document->document_id }}"
                                class="btn btn-primary">ดูรายละเอียด</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @endif
</div>
@endsection