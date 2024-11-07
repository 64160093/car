<!DOCTYPE html>
<html>
<head>
    <title>Document Report</title>
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
            margin-right: 0.2in;   /* ขอบขวา 1.5 เซนติเมตร */
            line-height: 1; /* ปรับ line-height ให้ชิดกันมากขึ้น */

        }
        h1 {
            text-align: center;
            font-weight: bold;
        }
        .content p {
            margin: 0;
            padding: 0;
        }
        .line {
            border-bottom: 1px dotted black;
            width: 248px;
            display: inline-block;
            height: 20px;
            padding-left: 30px;"
        }
        .signature {
            margin-left: 45%;
            overflow-wrap: break-word;
            line-height: 0.89;
        }
        .signature .line {
            border-bottom: 1px dotted black;
            width: 245px;
            display: inline-block;
            height: 20px;
            padding-left: 0px;"
        }

        .signature .content {
            margin-left: 20%;
        }

        .overlay-image {
            position: absolute;
            top: 0.1in; /* ปรับตำแหน่งแนวตั้ง */
            left: 0.5in; /* ปรับตำแหน่งแนวนอน */
            /* transform: translateX(-50%);  */
            width: 75px;
            height: auto;
            z-index: 10; /* ทำให้รูปภาพอยู่ด้านหน้า */
        }

        .overlay-cancel {
            position: absolute;
            top: 0in; /* ปรับตำแหน่งแนวตั้ง */
            left: 6in; /* ปรับตำแหน่งแนวนอน */
            transform: translateY(-0.4in); 
            z-index: 10; /* ทำให้รูปภาพอยู่ด้านหน้า */
        }

        .overlay-signatureUser {
            position: absolute;
            top: 6.1in; /* ปรับตำแหน่งแนวตั้ง */
            left: 0in; /* ปรับตำแหน่งแนวนอน */
            transform: translateX(4in); 
            z-index: 10; /* ทำให้รูปภาพอยู่ด้านหน้า */
        }
        
        .overlay-signatureDivsion {
            position: absolute;
            top: 6.75in; 
            left: 0in; 
            transform: translateX(4in); 
            z-index: 10; 
        }

        .overlay-signatureDepartment {
            position: absolute;
            top: 6.5in; 
            left: 1.5in; 
            transform: translateX(4in); 
            z-index: 10; 
        }

        .overlay-signatureOpcar {
            position: absolute;
            top: 7.5in; 
            left: 0in; 
            transform: translateX(4in); 
            z-index: 10; 
        }
        
        .overlay-signatureOfficer {
            position: absolute;
            top: 7.9in; 
            left: 0in; 
            transform: translateX(4in); 
            z-index: 10;
        }

        .overlay-signatureDirector {
            position: absolute;
            top: 8.2in; 
            left: 2.8in; 
            transform: translateX(-0.2in); 
            z-index: 10;
        }

    </style>
</head>
<body>

    @if ( $documents->cancel_allowed != 'pending'  )
        @if ($documents->cancel_admin == 'Y' && $documents->cancel_director == 'Y')
            <img src="{{ $imageCancel }}" alt="Cancel" class="overlay-cancel" width="120">
        @elseif ($documents->allow_director == 'pending' && $documents->cancel_reason != null)
            @if ($documents->cancel_admin == 'Y')
                <img src="{{ $imageCancel }}" alt="Cancel" class="overlay-cancel" width="120">
            @endif
        @elseif ($documents->allow_director != 'pending' && $documents->cancel_reason != null)
            @if ($documents->cancel_admin != 'Y')
            @elseif ($documents->cancel_admin == 'Y' && $documents->cancel_director != 'Y')
            @elseif ($documents->cancel_admin == 'Y' && $documents->cancel_director == 'Y')
                <img src="{{ $imageCancel }}" alt="Cancel" class="overlay-cancel" width="120">
            @endif
        @else
            <img src="{{ $imageCancel }}" alt="Cancel" class="overlay-cancel" width="120">
        @endif
    @endif

    <img src="{{ $imagePathPublic  }}" alt="BIMS Logo" class="overlay-image">
    <h1>บันทึกข้อความ</h1>
    <div class="content">
        <p>
            <b>ส่วนงาน </b><span class="line">
                @foreach($documents->reqDocumentUsers as $docUser)
                    {{ $docUser->division->division_name }}
                @endforeach
        </span> โทร <span class="line" ></span>
        </p>
        <p>
            <b>ที่ </b><span class="line" style="width: 283px;">
            </span><b> วันที่ </b><span class="line" style="width: 245px;">
                {{ \Carbon\Carbon::parse($documents->reservation_date)->format('d') }}
                {{ \Carbon\Carbon::parse($documents->reservation_date)->locale('th')->translatedFormat('F') }} พ.ศ.
                {{ \Carbon\Carbon::parse($documents->reservation_date)->format('Y') }}
            </span> 
        </p>
        
        <p style="margin-bottom: 13;"><b style="margin-right: 5; margin-bottom: 20;">เรื่อง </b>ขออนุญาตใช้รถยนต์สถาบันวิจัยทางทะเล</p>
        <p>เรียน <span style="margin-left: 8;"> ผู้อำนวยการ</span></p>
        <p style="margin-left: 35;">ด้วยข้าพเจ้า (นาย/นาง/นางสาว) <span class="line" style="width: 385px;">
            @foreach($documents->reqDocumentUsers as $docUser)
                {{ $docUser->name }} {{ $docUser->lname }}
            @endforeach
        </span></p>
        <p>พร้อมด้วย 
            <span class="line" style="width: 548px;">
                @php
                    $companionIds = explode(',', $documents->companion_name);
                    $companions = \App\Models\User::whereIn('id', $companionIds)->get(); 
                @endphp
                    @foreach($companions as $companion)
                        {{ $companion->name }} {{ $companion->lname }} 
                    @endforeach
            </span>
        </p>
        <p><span class="line" style="width: 605px; height: 20px; padding-left: 30px;"></span></p>
        <p><span class="line" style="width: 605px; line-height: 1; padding-left: 30px; margin-top: 4px;"></span></p>
        <p><span class="line" style="width: 605px; line-height: 1; padding-left: 30px; margin-top: 4px;"></span></p>
        <p >รวมทั้งสิ้น <span class="line" style="width: 70px; margin-top: 10px;" >{{ $documents->sum_companion + 1}}</span> คน 
            ขออนุญาตใช้รถยนต์ประเภท <span class="line" style="width: 271px; line-height: ;">{{ $documents->car_type }}</span>
        </p>
        <p>มีความประสงค์ขออนุญาตใช้รถยนต์เพื่อไป <span class="line" style="width: 380px;"> {{ $documents->objective }}</span></p>
        <p><span class="line" style="width: 605px; line-height: 1; padding-left: 30px; margin-bottom: 2px;"></span></p>

        <p style="margin-top: 4px;">ที่ <span class="line" style="width: 592px;">{{ $documents->location }}</span></p>
        <p>
            ตำบล/แขวง <span class="line" style="width: 121px;">{{ $documents->district->name_th }}
            </span> อำเภอ/เขต <span class="line" style="width: 121px;">{{ $documents->amphoe->name_th }}</span>
            </span> จังหวัด <span class="line" style="width: 121px;">{{ $documents->province->name_th }}</span>
        </p>
        <p>โดยให้รถยนต์ไปรับที่ <span class="line" style="width: 493px;">{{ $documents->car_pickup }}</span></p>
        <p>
            ออกเดินทางในวันที่ <span class="line" style="width: 40px;">
            {{  \Carbon\Carbon::parse($documents->start_date)->format('d') }}
            </span> เดือน <span class="line" style="width: 130px;">
            {{  \Carbon\Carbon::parse($documents->start_date)->locale('th')->translatedFormat('F') }}
            </span>
            </span> พ.ศ. <span class="line" style="width: 30px; padding-left: 10px; padding-right: 10px;">
            {{ \Carbon\Carbon::parse($documents->start_date)->addYears(543)->format('Y') }}            </span>
            </span> เวลา <span class="line" style="width: 102px;">
            {{  \Carbon\Carbon::parse($documents->start_time)->format('H:i') }}</span> น.
        </p>
        <p>
            และกลับในวันที่ <span class="line" style="width: 59px;">
            {{  \Carbon\Carbon::parse($documents->end_date)->format('d') }}
            </span> เดือน <span class="line" style="width: 130px;">
            {{  \Carbon\Carbon::parse($documents->end_date)->locale('th')->translatedFormat('F') }}
            </span>
            </span> พ.ศ. <span class="line" style="width: 30px; padding-left: 10px; padding-right: 10px;">
            {{ \Carbon\Carbon::parse($documents->end_date)->addYears(543)->format('Y') }}            </span>
            </span>
            </span> เวลา <span class="line" style="width: 101px;">            
            {{  \Carbon\Carbon::parse($documents->end_time)->format('H:i') }}</span> น.
        </p>
        <p style="margin-left: 35; line-height: 1.7;">จึงเรียนมาเพื่อโปรดพิจารณาอนุญาต จะเป็นพระคุณยิ่ง</p>
        
        <!-- ลายเซ็นคนขอ -->
        <div class="overlay-signatureUser">
            @foreach($documents->reqDocumentUsers as $docUser)          
                @php
                    $signaturePath = storage_path('app/signatures/' . $docUser->signature_name);
                @endphp
                <img src="{{ $signaturePath }}" width="200" class="img-fluid mt-2 borde" >
            @endforeach
        </div>
    
        <div class="signature">
            <p style="line-height: 1.7;">ลงชื่อ <span class="line"  ></span> ผู้ขออนุญาต</p>
            <p style="margin-left: 25px;">
                ( <span class="line" style="display: inline-block; text-align: center;">
                    @foreach($documents->reqDocumentUsers as $docUser)
                        {{ $docUser->name }} {{ $docUser->lname }}
                    @endforeach
                </span> 
                )
            </p>
            <p style="line-height: 1; margin-left: 25; padding-top: 8;"><span class="line" style="text-align: center;">
                @if ( $documents->allow_division == 'approved')
                <!-- หัวหน้าฝ่าย -->
                    <a class="overlay-signatureDivision">
                        @php
                            $divisionAllowByUser = $documents->DivisionAllowBy;
                            $signaturePath = null;
                            if ($divisionAllowByUser) {
                                $signaturePath = storage_path('app/signatures/' . $divisionAllowByUser->signature_name);
                            }
                        @endphp
                        <img src="{{ $signaturePath }}" width="200" class="img-fluid mt-2" >
                    </a>
                @endif
                </span> หัวหน้าฝ่าย
            </p>
        </div>
        <p>
            โปรดพิจารณาอนุญาตให้ใช้รถยนต์หมานเลขทะเบียน <span class="line" style="width:100px; padding-left: 10px;">
            {{ $documents->vehicle ? $documents->vehicle->car_category : ''}} 
            {{ $documents->vehicle ? $documents->vehicle->car_regnumber :''}} 
            <!-- {{ $documents->vehicle ? $documents->vehicle->car_province : ''}} -->
            </span> มี <span class="line" style="width: 200px;">
            {{ $documents->carmanUser ? $documents->carmanUser->name : ''}} 
            {{ $documents->carmanUser ? $documents->carmanUser->lname : ''}}
        </span></p>
        <p>เป็นพนักงานขับรถยนต์ และ <span class="line" style="width: 373px;">
         {{ $documents->carController ? $documents->carController->name : ''}} {{ $documents->carController ? $documents->carController->lname : ''}}
        </span> ควบคุมรถยนต์</p>
        
        <p style="line-height: 1; padding-top: 5.5%;">คำสั่งของผู้อำนวยการ <span class="line" style="width: 487px;">
            @if ( $documents->allow_director == 'approved' ) 
                อนุญาต
            @elseif ( $documents->allow_director == 'rejected' )
                ไม่อนุญาต เนื่องจาก {{ $documents->notallowed_reason }}
            @endif
        </span></p>
        <p><span class="line" style="width: 605px; line-height: 0.9; "></span></p>

        

<!-- ลายเซ็น -->

<div class="signature">
            <div class="content">
                @if ( $documents->allow_opcar == 'approved' && $documents->allow_officer != 'approved')
                    
                @elseif ( $documents->allow_opcar == 'approved' && $documents->allow_officer == 'approved')
                   
                @endif
            </div>
        </div>

        @if ( $documents->allow_department == 'approved')
        <!-- หัวหน้างาน -->
                <a class="overlay-signatureDepartment">
                    @php
                        $departmentAllowByUser = $documents->DepartmentAllowBy;
                        $signaturePath = null;

                        if ($departmentAllowByUser) {
                            $signaturePath = storage_path('app/signatures/' . $departmentAllowByUser->signature_name);
                        }
                    @endphp
                    <img src="{{ $signaturePath }}" width="200" class="img-fluid mt-2" >
                </a>
        @endif
        
        @if ( $documents->allow_opcar == 'approved' )
            <!-- คนสั่งรถ -->
            <a class="overlay-signatureOpcar">
                @php
                    $opcarAllowByUser = $documents->OpcarAllowBy;
                    $signaturePath = null;
                    if ($opcarAllowByUser) {
                        $signaturePath = storage_path('app/signatures/' . $opcarAllowByUser->signature_name);
                    }
                @endphp
                <img src="{{ $signaturePath }}" width="200" class="img-fluid mt-2" >
            </a>
        @endif

        @if ( $documents->allow_officer == 'approved' )
            <!-- หัวหน้าสำนักงาน -->
            <a class="overlay-signatureOfficer">
                @php
                    $officerAllowByUser = $documents->OfficerAllowBy;
                    $signaturePath = null;

                    if ($officerAllowByUser) {
                        $signaturePath = storage_path('app/signatures/' . $officerAllowByUser->signature_name);
                    }
                @endphp
                <img src="{{ $signaturePath }}" width="200" class="img-fluid mt-2" >
            </a>
        @endif
        

        <!-- ผอ. -->
        <a class="overlay-signatureDirector">
            @php
                $directorAllowByUser = $documents->DirectorAllowBy;
                $signaturePath = null;
                if ($directorAllowByUser) {
                    $signaturePath = storage_path('app/signatures/' . $directorAllowByUser->signature_name);
                }
            @endphp
            <img src="{{ $signaturePath }}" width="200" class="img-fluid mt-2" >
        </a>
    </div>
</body>
</html>
