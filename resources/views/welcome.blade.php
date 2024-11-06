@extends('layouts.app')
@section('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Home</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
@endsection

@section('content')
<div class="container mt-5">
    <div class="row mb-3">
        <div class="col-md-6 justify-content-start">
            <a href="{{ route('reqdocument.create') }}" class="btn btn-primary">{{ __('ขออนุญาตใช้พาหนะ') }}</a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div id="calendar" style="width: 97%; height: 97vh; margin: 0 auto;"></div>
        </div>
    </div>
</div>

<!-- Modal สำหรับแสดงรายละเอียดของ Event -->
<div class="modal fade" id="eventDetailModal" tabindex="-1" role="dialog" aria-labelledby="eventDetailModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventTitle"></h5>
                <!-- กากบาทปิด modal ถูกเอาออกแล้ว -->
            </div>
            <div class="modal-body">
                <p><strong>วัตถุประสงค์:</strong> <span id="eventObjective"></span></p>
                <p><strong>ชื่อผู้ขอ:</strong> <span id="eventRequester"></span></p>
                <p><strong>สถานที่:</strong> <span id="eventLocation"></span></p>
                <p><strong>ประเภทรถ:</strong> <span id="eventVehicleType"></span></p>
                <p><strong>วันที่ไป :</strong> <span id="eventStart"></span></p>
                <p><strong>วันที่กลับ :</strong> <span id="eventEnd"></span></p>
            </div>
            <div class="modal-footer">
                <!-- ปุ่มปิด modal สีแดง -->
                <button type="button" class="btn btn-danger"
                    onclick="$('#eventDetailModal').modal('hide');">ปิด</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

<!-- Moment.js ถูกย้ายมาไว้ที่ส่วนท้ายเพื่อให้แน่ใจว่าโหลดก่อนการใช้งาน -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/th.js"></script>

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
            right: 'dayGridMonth,listMonth',
        },
        buttonText: {
            today: 'วันนี้',
            month: 'เดือน',
            list: 'รายการ'
        },
        initialView: 'dayGridMonth',
        locale: 'th',
        editable: false,
        events: '/events',
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        eventClick: function (info) {
            // ตั้งค่าข้อมูลใน Modal
            $('#eventTitle').text('เอกสารที่: ' + info.event.id);  // เพิ่มคำว่า "เอกสารที่" ก่อนแสดง ID
            $('#eventObjective').text(info.event.extendedProps.objective); // ดึง objective ที่ส่งมาจาก Controller
            $('#eventRequester').text(info.event.extendedProps.requester_name);  // แสดงชื่อผู้ขอ
            $('#eventLocation').text(info.event.extendedProps.location);  // แสดงสถานที่
            $('#eventVehicleType').text(info.event.extendedProps.vehicle_type);  // แสดงประเภทรถ

            // ใช้ moment.js พร้อมกำหนด UTC offset เป็น +7 (Asia/Bangkok)
            var eventStart = moment(info.event.start).utcOffset('+07:00');  // แปลงเวลาให้ตรงกับ Bangkok
            var eventEnd = moment(info.event.end).utcOffset('+07:00');      // แปลงเวลาให้ตรงกับ Bangkok

            // ตั้งค่าข้อมูลเวลาใน Modal
            $('#eventStart').text(eventStart.format('DD MMM YYYY  เวลาไป : HH:mm น.'));
            $('#eventEnd').text(eventEnd.format('DD MMM YYYY  เวลากลับ :  HH:mm น.'));

            // แสดง Modal
            $('#eventDetailModal').modal('show');
        }

    });
    calendar.render();
</script>

@endsection