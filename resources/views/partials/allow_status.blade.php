@if ($document->allow_division == 'pending')
    <span class="badge bg-warning">รอหัวหน้าฝ่ายพิจารณา</span>
@elseif ($document->allow_division == 'approved')
    @if ($document->allow_opcar == 'pending')
        <span class="badge bg-warning">รอคนสั่งรถพิจารณา</span>
    @elseif ($document->allow_opcar == 'approved')
        @if ($document->allow_officer == 'pending')
            <span class="badge bg-warning">รอหัวหน้าสำนักงานพิจารณา</span>
        @elseif ($document->allow_officer == 'approved')
            @if ($document->allow_director == 'pending')
                <span class="badge bg-warning">รอผู้อำนวยการพิจารณา</span>
            @elseif ($document->allow_director == 'approved')
                <span class="badge bg-success">อนุมัติคำร้อง</span>
            @else
                <span class="badge bg-danger">ผู้อำนวยการไม่อนุมัติ</span>
                @if ($document->notallowed_reason)
                    <br><span>เหตุผล : {{ $document->notallowed_reason }}</span>
                @endif
            @endif
        @else
            <span class="badge bg-danger">หัวหน้าสำนักงานไม่อนุมัติ</span>
            @if ($document->notallowed_reason)
                <br><span>เหตุผล : {{ $document->notallowed_reason }}</span>
            @endif
        @endif
    @else
        <span class="badge bg-danger">คนสั่งรถไม่อนุมัติ</span>
        @if ($document->notallowed_reason)
            <br><span>เหตุผล : {{ $document->notallowed_reason }}</span>
        @endif
    @endif
@else
    <span class="badge bg-danger">หัวหน้าฝ่ายไม่อนุมัติ</span>
    @if ($document->notallowed_reason)
        <br><span>เหตุผล : {{ $document->notallowed_reason }}</span>
    @endif
@endif
