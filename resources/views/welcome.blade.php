@extends('layouts.app')
@section('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Home</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/th.js"></script>
@endsection

@section('content')
<div class="container mt-5">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-md-6 justify-content-start">
            <a href="{{ route('reqdocument.create') }}" class="btn btn-primary">{{ __('ขออนุญาตใช้พาหนะ') }}</a>
        </div>
        <div class="col-md-6 d-flex justify-content-end">
            <button id="exportButton" class="btn btn-success">{{ __('ส่งออกเอกสาร') }}</button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div id="calendar" style="width: 97%; height: 97vh; margin: 0 auto;"></div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listMonth'
        },
        initialView: 'dayGridMonth',
        timeZone: 'Asia/Bangkok', 
        locale: 'th', // ตั้งค่าภาษาเป็นไทย
        editable: true,

        events: '/events', // ดึงข้อมูล events จาก Controller

        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false // false สำหรับเวลาแบบ 24 ชั่วโมง
        },

        // Event Resizing
        eventDrop: function(info) {
            info.revert();
            alert('You cannot move events to another day.');
        },

        // Event Resizing
        eventResize: function (info) {
            var eventId = info.event.id;
            var newEndDate = info.event.end;
            var newEndDateUTC = newEndDate.toISOString().slice(0, 10);

            $.ajax({
                method: 'post',
                url: `/schedule/${eventId}/resize`,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    end_date: newEndDateUTC
                },
                success: function () {
                    console.log('Event resized successfully.');
                },
                error: function (error) {
                    console.error('Error resizing event:', error);
                }
            });
        },
    });

    calendar.render();

    // ฟังก์ชันเพื่อส่งออกข้อมูล
    function exportEvents() {
        var events = calendar.getEvents().map(function (event) {
            return {
                title: event.title,
                start: event.start ? event.start.toISOString() : null,
                end: event.end ? event.end.toISOString() : null,
                color: event.backgroundColor,
            };
        });

        var wb = XLSX.utils.book_new();
        var ws = XLSX.utils.json_to_sheet(events);
        XLSX.utils.book_append_sheet(wb, ws, 'Events');

        var arrayBuffer = XLSX.write(wb, {
            bookType: 'xlsx',
            type: 'array'
        });

        var blob = new Blob([arrayBuffer], {
            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        });

        var downloadLink = document.createElement('a');
        downloadLink.href = URL.createObjectURL(blob);
        downloadLink.download = 'events.xlsx';
        downloadLink.click();
    }

    // ฟังก์ชันที่ใช้เมื่อกดปุ่มส่งออก
    document.getElementById('exportButton').addEventListener('click', exportEvents);
</script>

@endsection
