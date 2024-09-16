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
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-6 justify-content-start">
            <a href="{{ route('reqdocument.create') }}" class="btn btn-primary">{{ __('Add Event') }}</a>

        </div>
        <div class="col-md-6 d-flex justify-content-end">
            <button id="exportButton" class="btn btn-success">{{ __('Export Calendar') }}</button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div id="calendar" style="width: 100%;height:100vh"></div>

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
    var events = [];
    var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listMonth'
        },
        initialView: 'dayGridMonth',
        timeZone: 'Asia/Bangkok', editable: true,

        events: [
            {
                id: '1',
                title: 'Meeting with Team',
                start: '2024-08-15T10:00:00',
                end: '2024-08-15T11:00:00',
                backgroundColor: '#f39c12', // สีพื้นหลัง
                borderColor: '#f39c12' // สีขอบ
            },
            {
                id: '2',
                title: 'Project Deadline',
                start: '2024-08-20',
                backgroundColor: '#e74c3c',
                borderColor: '#e74c3c'
            },
            {
                id: '3',
                title: 'Workshop',
                start: '2024-08-25T09:00:00',
                end: '2024-08-25T12:00:00',
                backgroundColor: '#3498db',
                borderColor: '#3498db'
            },
            {
                id: '4',
                title: 'Company Event',
                start: '2024-08-30',
                backgroundColor: '#2ecc71',
                borderColor: '#2ecc71'
            }
        ],

        // Deleting The Event
        // eventContent: function (info) {
        //     var eventTitle = info.event.title;
        //     var eventElement = document.createElement('div');
        //     eventElement.innerHTML = '<span style="cursor: pointer;">❌</span> ' + eventTitle;

        //     eventElement.querySelector('span').addEventListener('click', function () {
        //         if (confirm("Are you sure you want to delete this event?")) {
        //             var eventId = info.event.id;
        //             $.ajax({
        //                 method: 'get',
        //                 url: '/schedule/delete/' + eventId,
        //                 headers: {
        //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //                 },
        //                 success: function (response) {
        //                     console.log('Event deleted successfully.');
        //                     calendar.refetchEvents(); // Refresh events after deletion
        //                 },
        //                 error: function (error) {
        //                     console.error('Error deleting event:', error);
        //                 }
        //             });
        //         }
        //     });
        //     return {
        //         domNodes: [eventElement]
        //     };
        // },

        // Drag And Drop

        // eventDrop: function (info) {
        //     var eventId = info.event.id;
        //     var newStartDate = info.event.start;
        //     var newEndDate = info.event.end || newStartDate;
        //     var newStartDateUTC = newStartDate.toISOString().slice(0, 10);
        //     var newEndDateUTC = newEndDate.toISOString().slice(0, 10);

        //     $.ajax({
        //         method: 'post',
        //         url: `/schedule/${eventId}`,
        //         data: {
        //             '_token': "{{ csrf_token() }}",
        //             start_date: newStartDateUTC,
        //             end_date: newEndDateUTC,
        //         },
        //         success: function () {
        //             console.log('Event moved successfully.');
        //         },
        //         error: function (error) {
        //             console.error('Error moving event:', error);
        //         }
        //     });
        // },

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

    // Exporting Function
    document.getElementById('exportButton').addEventListener('click', function () {
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
    })
</script>
@endsection