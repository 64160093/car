@extends('layouts.app')

@section('content')
<div class="container">

<h2 class="text-center mb-4">ประวัติการยื่นขอ</h2>
    <div class="container-fluid mt-2">
        <form method="GET" action="{{ route('documents.search') }}">
            <div class="d-flex align-items-center">
                <input type="search" id="searchName" name="q" class="form-control me-2" placeholder="ค้นหาข้อมูล"
                    aria-label="Search" value="{{ request()->get('q') }}">
                <button type="submit" class="btn btn-primary">ค้นหา</button>
                {{-- Dropdown สำหรับกรองสถานะ --}}
                <select name="filter" id="filter" class="form-select me-2 ms-2" style="max-width: 200px;"
                    onchange="this.form.submit()">
                    <option value="">ทั้งหมด</option>
                    <option value="completed" {{ request('filter') == 'completed' ? 'selected' : '' }}>สำเร็จแล้ว</option>
                    <option value="pending" {{ request('filter') == 'pending' ? 'selected' : '' }}>พิจารณา</option>
                    <option value="cancelled" {{ request('filter') == 'cancelled' ? 'selected' : '' }}>ไม่อนุมัติ/ยกเลิก
                    </option>
                </select>
            </div>
    </div>

    @if($documents->isEmpty())
        <div class="alert alert-info mt-2">
            {{ __('ไม่มีฟอร์มสำหรับการตรวจสอบ') }}
        </div>
    @else
            {{-- จัดกลุ่มเอกสารตามประเภทที่เลือก --}}
            @php
                $groupedBy = request('filter') == 'travel' ? 'start_date' : 'reservation_date';
            @endphp

            @foreach($documents->groupBy(function ($date) use ($groupedBy) {
                        return \Carbon\Carbon::parse($date->$groupedBy)->format('F Y', 'th');
                    }) as $month => $groupedDocuments)
                    <div class="card mb-4 mt-2">
                        {{-- Header ของการ์ดจะแสดงเดือน --}}
                        <div class="card-header text-white bg-primary">{{ $month }}</div>
                        <div class="card-body">
                            @foreach($groupedDocuments as $document)
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        @php
                                            // ตรวจสอบสถานะและกำหนด $borderColor
                                            $borderColor = '';

                                            if ($document->cancel_allowed == 'rejected') {
                                                $borderColor = 'border-secondary'; // สีเทาถ้าเอกสารถูกยกเลิก
                                            } else {

                                                // ตรวจสอบ division_id == 2 หรือไม่
                                                $hasDivisionTwo = false;
                                                foreach ($document->reqDocumentUsers as $docUser) {
                                                    if ($docUser->division_id == 2) {
                                                        $hasDivisionTwo = true;
                                                        break;
                                                    }
                                                }

                                                // ถ้ามี division_id == 2 ให้ตรวจสอบ allow_department
                                                if ($hasDivisionTwo) {
                                                    if ($document->allow_department == 'pending') {
                                                        $borderColor = 'border-warning';
                                                    } elseif ($document->allow_department == 'approved') {
                                                        if ($document->allow_division == 'pending') {
                                                            $borderColor = 'border-warning';
                                                        } elseif ($document->allow_division == 'approved') {
                                                            if ($document->allow_opcar == 'pending') {
                                                                $borderColor = 'border-warning'; // สีเหลืองถ้ารอคนสั่งรถพิจารณา
                                                            } elseif ($document->allow_opcar == 'approved') {
                                                                if ($document->allow_officer == 'pending') {
                                                                    $borderColor = 'border-warning'; // สีเหลืองถ้ารอหัวหน้าสำนักงานพิจารณา
                                                                } elseif ($document->allow_officer == 'approved') {
                                                                    if ($document->allow_director == 'pending') {
                                                                        $borderColor = 'border-warning'; // สีเหลืองถ้ารอผู้อำนวยการพิจารณา
                                                                    } elseif ($document->allow_director == 'approved') {
                                                                        $borderColor = 'border-success'; // สีเขียวถ้าอนุมัติคำร้อง
                                                                    } else {
                                                                        $borderColor = 'border-danger';
                                                                    }
                                                                } else {
                                                                    $borderColor = 'border-danger';
                                                                }
                                                            } else {
                                                                $borderColor = 'border-danger';
                                                            }
                                                        } else {
                                                            $borderColor = 'border-danger';
                                                        }
                                                    } else {
                                                        $borderColor = 'border-danger';
                                                    }
                                                } else {
                                                    // ถ้าไม่มี division_id == 2 ให้เริ่มที่ allow_division
                                                    if ($document->allow_division == 'pending') {
                                                        $borderColor = 'border-warning';
                                                    } elseif ($document->allow_division == 'approved') {
                                                        if ($document->allow_opcar == 'pending') {
                                                            $borderColor = 'border-warning'; // สีเหลืองถ้ารอคนสั่งรถพิจารณา
                                                        } elseif ($document->allow_opcar == 'approved') {
                                                            if ($document->allow_officer == 'pending') {
                                                                $borderColor = 'border-warning'; // สีเหลืองถ้ารอหัวหน้าสำนักงานพิจารณา
                                                            } elseif ($document->allow_officer == 'approved') {
                                                                if ($document->allow_director == 'pending') {
                                                                    $borderColor = 'border-warning'; // สีเหลืองถ้ารอผู้อำนวยการพิจารณา
                                                                } elseif ($document->allow_director == 'approved') {
                                                                    $borderColor = 'border-success'; // สีเขียวถ้าอนุมัติคำร้อง
                                                                } else {
                                                                    $borderColor = 'border-danger';
                                                                }
                                                            } else {
                                                                $borderColor = 'border-danger';
                                                            }
                                                        } else {
                                                            $borderColor = 'border-danger';
                                                        }
                                                    } else {
                                                        $borderColor = 'border-danger';
                                                    }
                                                }
                                            }
                                        @endphp


                                        <div class="d-flex justify-content-between align-items-center border p-3 rounded shadow-sm {{ $borderColor }}"
                                            style="background-color: #f8f9fa;">
                                            <div class="d-flex justify-content-between">
                                                <div style="flex: 2; margin-right: 40px;">
                                                    <strong style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">วัตถุประสงค์: {{ $document->objective }}</strong><br>        สถานที่: {{ $document->location }}<br>
                                                    วันที่ไป: 
                                                    <span>
                                                        {{ 
                                                            \Carbon\Carbon::parse($document->start_date)->format('d') . ' ' . 
                                                            \Carbon\Carbon::parse($document->start_date)->locale('th')->translatedFormat('F') . ' ' . 
                                                            \Carbon\Carbon::parse($document->start_date)->addYears(543)->format('Y') 
                                                        }}
                                                    </span><br>
                                                    เวลาไป: {{ \Carbon\Carbon::parse($document->start_time)->format('H:i') }} น.
                                                </div>

                                                <div style="flex: 2; white-space: nowrap; overflow: visible;">
                                                    <br>วันที่ทำเรื่อง: {{ \Carbon\Carbon::parse($document->reservation_date)->translatedFormat('d F Y') }}<br>
                                                    วันที่กลับ:
                                                    <span>
                                                        {{ 
                                                            \Carbon\Carbon::parse($document->end_date)->format('d') . ' ' . 
                                                            \Carbon\Carbon::parse($document->end_date)->locale('th')->translatedFormat('F') . ' ' . 
                                                            \Carbon\Carbon::parse($document->end_date)->addYears(543)->format('Y') 
                                                        }}
                                                    </span><br>        
                                                    เวลากลับ: {{ \Carbon\Carbon::parse($document->end_time)->format('H:i') }} น.
                                                </div>
                                            </div>



                                            <div>
                                                @if ( $document->cancel_allowed == 'pending' )
                                                    @foreach($document->reqDocumentUsers as $docUser)
                                                        @if ($docUser->division_id == 2)
                                                            @if ($document->allow_department == 'pending')
                                                                <span class="badge bg-warning">รอหัวหน้างานพิจารณา</span>
                                                            @elseif ($document->allow_department == 'approved')
                                                                @include('partials.allow_status', ['document' => $document])
                                                            @else
                                                                <span class="badge bg-danger">หัวหน้างานไม่อนุมัติ</span>
                                                                @if ($document->notallowed_reason)
                                                                    <br><span>เหตุผล: {{ $document->notallowed_reason }}</span>
                                                                @endif
                                                            @endif
                                                        @else
                                                            @include('partials.allow_status', ['document' => $document])
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <!-- ไม่แสดงอะไรหายไปเลย -->
                                                @endif

                                                @if ( $document->cancel_allowed == "rejected")
                                                    <a href="{{ route('documents.status') }}?id={{ $document->document_id }}" 
                                                    class="btn btn-outline-danger disabled" >รายการคำขอถูกยกเลิกแล้ว</a>  
                                                    <a href="{{ route('documents.review') }}?id={{ $document->document_id }}"
                                                    class="btn btn-secondary">ดูรายละเอียด</a>
                                                @else
                                                    <a href="{{ route('documents.review') }}?id={{ $document->document_id }}"
                                                        class="btn btn-primary">ดูรายละเอียด</a>
                                                    <a href="{{ route('documents.status') }}?id={{ $document->document_id }}" 
                                                        class="btn btn-outline-primary">สถานะ</a> 
                                                          
                                                @endif
                                                
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>



                    </div>
            @endforeach
    @endif
</div>

<style>
    .card-header {
        font-weight: bold;
        font-size: 1.25rem;
    }

    .shadow-sm {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection