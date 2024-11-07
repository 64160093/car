<!DOCTYPE html>
<html>

<head>
    <title>PDF with Thai Fonts</title>
    <style>
        @font-face {
            font-family: 'THSarabun';
            font-style: normal;
            font-weight: normal;
            src: url('{{ public_path('fonts/THSarabun.ttf') }}') format('truetype');
        }

        @font-face {
            font-family: 'THSarabun';
            font-style: normal;
            font-weight: bold;
            src: url('{{ public_path('fonts/THSarabun Bold.ttf') }}') format('truetype');
        }

        body {
            font-family: 'THSarabun', sans-serif;
            overflow-wrap: break-word;
            font-size: 20px;
            margin-left: 0.5in;
            margin-right: 0.2in;
            /* ขอบขวา 1.5 เซนติเมตร */
            line-height: 1.0;
            /* ปรับระยะห่างระหว่างบรรทัดให้ดูโปร่งขึ้น */
        }

        h1 {
            font-size: 22px;
            font-weight: bold;
            text-align: center;
            line-height: 0.75;
            /* ปรับระยะห่างระหว่างบรรทัดของ h1 */
        }

        .content p {
            margin: 0;
            padding: 0;
            line-height: 1.2;
            /* ปรับระยะห่างระหว่างบรรทัดของข้อความในเนื้อหา */
        }

        .line {
            border-bottom: 1px dotted black;
            width: 245px;
            display: inline-block;
            height: 25px;
            padding-left: 30px;
        }

        .longline {
            border-bottom: 2px dotted black;
            border-color: rgba(0, 0, 0, 0.8);
            width: 100%;
            display: inline-block;
            height: 5px;
            margin-bottom: 0.15in;

        }

        .number {
            position: absolute;
            top: 0.0001in;
            right: 0.005in;
            font-size: 18px;
        }

        .signature {
            position: absolute;
            bottom: 0;
            right: 0;
            margin-right: 0.2in;
            margin-bottom: 0.01in;
            overflow-wrap: break-word;
            line-height: 1.2;
            /* ปรับระยะห่างระหว่างบรรทัดในส่วนลายเซ็น */
        }

        .signature .line {
            border-bottom: 1px dotted black;
            width: 245px;
            display: inline-block;
            height: 25px;
            padding-left: 0px;
        }
        .signature .line2 {
            /* border-bottom: 1px dotted white; */
            width: 245px;
            width: 245px;
            display: inline-block;
            height: 25px;
            padding-left: 0px;
        }

        /* สร้างช่องกรอบสี่เหลี่ยมสำหรับติ๊ก */
        .checkbox {
            display: inline-block;
            width: 15px;
            height: 15px;
            border: 0.2px solid black;
            margin-right: 3px;
            margin-left: 25px;
            margin-top: 14px;
            vertical-align: middle;
        }
        .checkbox2 {
            display: inline-block;
            width: 15px;
            height: 15px;
            border: 0.2px solid black;
            margin-right: 3px;
            margin-left: 25px;
            margin-top: 14px;
            vertical-align: middle;
            background-color: lightblue; 
        }

        .overlay-signatureDirector {
            position: absolute;
            top: 4.65in; 
            left: 5.05in; 
            transform: translateX(-0.2in); 
            z-index: 10;
            
        }

        .overlay-signatureCarman {
            position: absolute;
            top: 9.7in; 
            left: 4.78in; 
            transform: translateX(-0.2in); 
            z-index: 10;
            
        }
        
    </style>
</head>

<body>
    <!-- จัดตำแหน่ง "เลขที่" ไว้ที่มุมขวาบนสุด -->
    <!-- <p class="number">เลขที่ ............/.............</p> -->

    <h1>สถาบันวิทยาศาสตร์ มหาวิทยาลัยบูรพา</h1>
    <h1>รายงานแจ้งพนักงานขับรถเพื่อทราบงานในแต่ละวัน</h1>

    <span class="longline"></span>

    <div class="content">
        <p><span class="section-title">เรื่อง</span><span style="margin-left: 8;">ขับรถยนต์ไปปฎิบัติงาน</span></p>
        <p>
            <span class="section-title ">ถึง</span><span class="line " style="width: 300px;">
                {{ $documents->carmanUser->name }} {{ $documents->carmanUser->lname }}
            </span>
            <span>หมายเลขทะเบียน</span><span class="line" style="width: 164px;">
                {{ $documents->vehicle->car_category }} {{ $documents->vehicle->car_regnumber }} {{ $documents->vehicle->car_province }}
            </span>
        </p>

        <p><span class="section-title" style="margin-left: 50; line-height: 30px">
            ด้วยสถาบันวิทยาศาสตร์ทางทะเล มหาวิทยาลัยบูรพา ได้อนุมัติให้บุคลากรไปปฏิบัติงานวันที่
            </span><span class="line" style="width: 61px;"></span></p>

        <p>
            <span class="line" style="width: 212px;">
                {{ \Carbon\Carbon::parse($documents->reservation_date)->format('d') }}
                {{ \Carbon\Carbon::parse($documents->reservation_date)->locale('th')->translatedFormat('F') }} พ.ศ. 
                {{ \Carbon\Carbon::parse($documents->reservation_date)->format('Y') }}
            </span>
            <span class="section-title">จึงให้ขับรถไปรับบุคลากร เวลา</span>
            <span class="line" style="width: 86px;">
                {{  \Carbon\Carbon::parse($documents->start_time)->format('H:i') }} น.</span>
            </span><span> เพื่อไปปฏิบัติงาน</span>
        </p>ตามสถานที่ดังต่อไปนี้

        <p><span class="section-title">1. </span><span class="line" style="width: 452px;">
            {{ $documents->location }} {{ $documents->district->name_th }} {{ $documents->amphoe->name_th }} {{ $documents->province->name_th }}
            </span><span>จำนวน</span>
            <span class="line" style="width: 50px;">{{ $documents->sum_companion }}</span>คน</p>
        <p><span class="section-title" style="margin-left:25px;">รับที่</span><span class="line">{{ $documents->car_pickup }}</span><span>
                เพื่อไปปฏิบัติงาน</span><span class="line" style="width: 188px;">{{ $documents->objective }}</span></p>

        <p><span class="section-title">2. </span><span class="line" style="width: 452px;"></span><span>
                จำนวน</span><span class="line" style="width: 50px;"></span>คน</p>
        <p><span class="section-title" style="margin-left:25px;">รับที่</span><span class="line"></span><span>
                เพื่อไปปฏิบัติงาน</span><span class="line" style="width: 188px;"></span></p>

        <p><span class="section-title">3. </span><span class="line" style="width: 452px;"></span><span>
                จำนวน</span><span class="line" style="width: 50px;"></span>คน</p>
        <p><span class="section-title" style="margin-left:25px;">รับที่</span><span class="line"></span><span>
                เพื่อไปปฏิบัติงาน</span><span class="line" style="width: 188px;"></span></p>

        <p><span class="line" style="width: 188px; margin-left: 416px; margin-top: 20px;"></span></p>

        <span class="longline" style="margin-top: 30px;"></span>

        <p><span class="section-title" >เรื่อง</span><span style="margin-left: 8; height: 0.5">รายงานผลการปฏิบัติ</span></p>
        <p><span class="section-title">เรียน</span> <span style="margin-left: 5;">ผู้อำนวยการ</span></p>

        <p><span class="section-title" style="margin-left:70px; line-height: 30px">ตามที่สถาบันวิทยาศาสตร์ทางทะเล มหาวิทยาลัยบูรพา
                อนุมัติให้ข้าพเจ้า</span><span class="line" style="padding-left: 15px; width: 150px; display: inline-block; white-space: nowrap; overflow: visible;">
                {{ $documents->carmanUser->name }} {{ $documents->carmanUser->lname }}</span></p>

        <p><span class="section-title">ขับรถยนต์หมายเลขทะเบียน</span><span class="line" style="width: 168px;">
        {{ $documents->vehicle->car_category }} {{ $documents->vehicle->car_regnumber }} {{ $documents->vehicle->car_province }}

            </span><span> ไปปฏิบัติงาน ณ จังหวัด</span><span class="line" style="width: 132px;">
                {{ $documents->province->name_th }}
            </span></p>

        <p><span class="section-title">โดยออกเดินทางเวลา</span><span class="line" style="width: 258px;">
        {{  \Carbon\Carbon::parse($report->stime)->format('H:i') }}</span>

        </span><span>ผู้ร่วมเดินทางจำนวน</span><span class="line" style="width: 81px;">{{ $report->total_companion }}</span>คน</p>

        <p><span class="section-title">หมายเลขกิโลเมตรก่อนเดินทาง</span><span class="line"
                style="width: 125px;">{{ $report->skilo_num }}</span>
                <span>หมายเลขกิโลเมตรหลังเดินทาง</span><span class="line"
                style="width: 125px;">{{ $report->ekilo_num }}</span></p>

        <p>
            @if ( $report->performance_isgood == 'Y' )
                <span class="checkbox2"></span>
                <span class="section-title" style="margin-left:10px;">การปฏิบัติงานครั้งนี้เป็นไปด้วยความเรียบร้อย</span>
            @else
                <span class="checkbox"></span>
                <span class="section-title" style="margin-left:10px;">การปฏิบัติงานครั้งนี้เป็นไปด้วยความเรียบร้อย</span>
            @endif
        </p>
        <p>
            @if ( $report->performance_isgood == 'N' )
                <span class="checkbox2"></span>
                <span class="section-title" style="margin-left:10px;">อื่นๆ</span>
                <span class="line" style="width: 518px;">{{ $report->comment_issue }}</span>
            @else
                <span class="checkbox"></span>
                <span class="section-title" style="margin-left:10px;">อื่นๆ</span>
                <span class="line" style="width: 518px;"></span>
            @endif
            
        </p>
        <p><span class="line" style="width: 580px;margin-left:25px"></span></p>

        <p><span>ค่าใช้จ่ายรวมที่ใช้ไป </span><span class="line"style="width: 100px;">{{ $report->total_cost }}</span> บาท</p>
        <p><span class="section-title">โดยกลับถึงสถาบันวิทยาศาสตร์ทางทะเล เวลา </span><span class="line" style="width: 60px; padding-left:15px">
            {{  \Carbon\Carbon::parse($report->stime)->format('H:i') }}</span>
            </span> น. และข้าพเจ้าได้ทำความสะอาดรถยนต์เป็นที่เรียบร้อยแล้ว
        <span class="section-title"> </span></p>
        
        <p><span class="section-title">เพื่อใช้สำหรับงานในวันต่อไป</span> </p>
        <p><span class="section-title" style="margin-left:70px;">จึงเรียนมาเพื่อโปรดทราบ</span> </p>

        <div class="signature">
            <p class="line" style=" text-align: center; display: inline-block; white-space: nowrap; overflow: visible; margin-left: 10px"> </p>
            <p><span>
            ( <span class="line" style=" text-align: center; display: inline-block; white-space: nowrap; overflow: visible;">
                {{ $documents->carmanUser->name }} {{ $documents->carmanUser->lname }}
            </span> 
            )
            </span>
            <p class="line2" style=" text-align: center; display: inline-block; white-space: nowrap; overflow: visible;">พนักงานขับรถ</p>  
        </div>
    </div>

    <!-- ลายเซ็น -->
        <a class="overlay-signatureDirector">
            @php
                $directorAllowByUser = $documents->DirectorAllowBy;
                $signaturePath = null;
                if ($directorAllowByUser) {
                    $signaturePath = storage_path('app/signatures/' . $directorAllowByUser->signature_name);
                }
            @endphp
            <img src="{{ $signaturePath }}" width="200" class="img-fluid mt-2">
        </a>
        <a class="overlay-signatureCarman">
            @php
                $carmanAllowByUser = $documents->CarmenAllowBy;
                $signaturePath = null;
                if ($carmanAllowByUser) {
                    $signaturePath = storage_path('app/signatures/' . $carmanAllowByUser->signature_name);
                }
            @endphp
            <img src="{{ $signaturePath }}" width="200" class="img-fluid mt-2">
        </a>

    
</body>

</html>