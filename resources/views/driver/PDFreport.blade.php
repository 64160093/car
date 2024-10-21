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
            line-height: 1.4;
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
            line-height: 1.25;
            /* ปรับระยะห่างระหว่างบรรทัดของข้อความในเนื้อหา */
        }

        .line {
            border-bottom: 1px dotted black;
            width: 245px;
            display: inline-block;
            height: 20px;
            padding-left: 30px;
        }

        .longline {
            border-bottom: 2px dotted black;
            /* ปรับความหนาเป็น 2px */
            border-color: rgba(0, 0, 0, 0.8);
            /* ทำให้สีดำเข้มขึ้น */
            width: 100%;
            display: inline-block;
            height: 10px;
            margin-bottom: 0.25in;

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
            height: 20px;
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
    </style>
</head>

<body>
    <!-- จัดตำแหน่ง "เลขที่" ไว้ที่มุมขวาบนสุด -->
    <p class="number">เลขที่ ............/.............</p>

    <h1>สถาบันวิทยาศาสตร์ มหาวิทยาลัยบูรพา</h1>
    <h1>รายงานแจ้งพนักงานขับรถเพื่อทราบงานในแต่ละวัน</h1>

    <span class="longline"></span>

    <div class="content">
        <p><span class="section-title">เรื่อง</span> <span>ขออนุญาตใช้รถยนต์สถาบันวิจัยทางทะเล</span></p>

        <p><span class="section-title">ถึง</span><span class="line"></span><span>หมายเลขทะเบียน</span><span class="line"
                style="width: 223px;"></span></p>

        <p><span class="section-title">ด้วยสถาบันวิทยาศาสตร์ทางทะเล มหาวิทยาลัยบูรพา
                ได้อนุมัติให้บุคลากรไปปฏิบัติงานวันที่</span><span class="line" style="width: 132px;"></span></p>

        <p><span class="section-title">จึงให้ขับรถไปรับบุคลากร เวลา</span><span class="line"
                style="width: 132px;"></span><span> เพื่อไปปฏิบัติงานตามสถานที่ดังต่อไปนี้</span></p>

        <p><span class="section-title">1. </span><span class="line" style="width: 452px;"></span><span>
                จำนวน</span><span class="line" style="width: 50px;"></span>คน</p>
        <p><span class="section-title" style="margin-left:25px;">รับที่</span><span class="line"></span><span>
                เพื่อไปปฏิบัติงาน</span><span class="line" style="width: 188px;"></span></p>

        <p><span class="section-title">2. </span><span class="line" style="width: 452px;"></span><span>
                จำนวน</span><span class="line" style="width: 50px;"></span>คน</p>
        <p><span class="section-title" style="margin-left:25px;">รับที่</span><span class="line"></span><span>
                เพื่อไปปฏิบัติงาน</span><span class="line" style="width: 188px;"></span></p>

        <p><span class="section-title">3. </span><span class="line" style="width: 452px;"></span><span>
                จำนวน</span><span class="line" style="width: 50px;"></span>คน</p>
        <p><span class="section-title" style="margin-left:25px;">รับที่</span><span class="line"></span><span>
                เพื่อไปปฏิบัติงาน</span><span class="line" style="width: 188px;"></span></p>

        <p><span class="line" style="width: 188px; margin-left: 416px;margin-top: 30px;"></span></p>

        <span class="longline" style="margin-top: 30px;"></span>

        <p><span class="section-title" >เรื่อง</span> <span>รานงานผลการปฏิบัติ</span></p>
        <p><span class="section-title">เรียน</span> <span>ผู้อำนวยการ</span></p>

        <p><span class="section-title" style="margin-left:25px;">ตามที่สถาบันวิทยาศาสตร์ทางทะเล มหาวิทยาลัยบูรพา
                อนุมัติให้ข้ามเจ้า</span><span class="line" style="width: 207px;"></span></p>

        <p><span class="section-title">ขับรถยนต์หมายเลขทะเบียน</span><span class="line"
                style="width: 168px;"></span><span> ไปปฏิบัติงาน ณ จังหวัด</span><span class="line"
                style="width: 132px;"></span></p>

        <p><span class="section-title">โดยออกเดินทางเวลา</span><span class="line" style="width: 258px;"></span><span>
                ผู้ร่วมเดินทางจำนวน</span><span class="line" style="width: 81px;"></span>คน</p>

        <p><span class="section-title">หมายเลขกิโลเมตรก่อนเดินทาง</span><span class="line"
                style="width: 127px;"></span><span>หมายเลขกิโลเมตรหลังเดินทาง</span><span class="line"
                style="width: 128px;"></span></p>

        <p>
            <span class="checkbox"></span>
            <span class="section-title" style="margin-left:10px;">การปฏิบัติงานครั้งนี้เป็นไปด้วยความเรียบร้อย</span>
        </p>
        <p>
            <span class="checkbox"></span>
            <span class="section-title" style="margin-left:10px;">อื่นๆ</span>
            <span class="line" style="width: 518px;"></span>
        </p>
        <p><span class="line" style="width: 580px;margin-left:25px"></span></p>

        <p><span class="section-title">โดยกลับถึงสถาบันวิทยาศาสตร์ทางทะเล เวลา </span><span class="line"
                style="width: 100px;"></span> น. </p>
        <p><span>ค่าใช้จ่ายรวมที่ใช้ไป</span><span class="line"style="width: 100px;"></span>บาท</p>
        <p><span class="section-title">และข้าพเจ้าได้ทำความสะอาดรถยนต์เป็นที่เรียบร้อยแล้ว </span></p>
        <p><span class="section-title">เพื่อใช้สำหรับงานในวันต่อไป</span> </p>
        <p><span class="section-title" style="margin-left:25px;">จึงเรียนมาเพื่อโปรดทราบ</span> </p>

        <div class="signature">
            <p class="line" style="width: 188px;"> </p>
            <p><span>(</span><span class="line" style="width: 188px;"></span><span>)</span></p>
            <p style="margin-left:60px;">พนักงานขับรถ</p>
        </div>
    </div>
</body>

</html>