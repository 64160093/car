 /**
     *
     *
     * 
     */
// ---------------------เช็ค division and departmen ของ register---------------------
document.addEventListener('DOMContentLoaded', function () {
    const divisionSelect = document.getElementById('division');
    const departmentGroup = document.getElementById('department-group');
    const departmentSelect = document.getElementById('department');

    // ฟังก์ชันตรวจสอบค่า division
    function toggleDepartmentField() {
        console.log('Selected division:', divisionSelect.value); // ตรวจสอบค่า division
        if (divisionSelect.value == '2') {
            departmentGroup.style.display = 'block'; // แสดงฟิลด์ฝ่ายงาน
        } else {
            departmentGroup.style.display = 'none'; // ซ่อนฟิลด์ฝ่ายงาน
            departmentSelect.value = ''; // เคลียร์ค่าเมื่อซ่อนฝ่ายงาน
        }
    }

    toggleDepartmentField(); // เรียกฟังก์ชันเมื่อหน้าโหลดขึ้นมา (เพื่อตรวจสอบค่าเก่า)

    // เรียกฟังก์ชันเมื่อเปลี่ยนค่าใน dropdown
    divisionSelect.addEventListener('change', toggleDepartmentField);
});



 /**
     *
     *
     * 
     */
// ---------------------ปรับค่าการแสดงเวลา หน้า reqDocument---------------------
document.addEventListener('DOMContentLoaded', function () {
    function convertToBuddhistDate(date) {
        var buddhistYear = date.getFullYear() + 543;
        return buddhistYear + '-' +
            String(date.getMonth() + 1).padStart(2, '0') + '-' +
            String(date.getDate()).padStart(2, '0');
    }

    var todayUTC = new Date();
    var todayThailand = new Date(todayUTC.getTime() + (7 * 60 * 60 * 1000));
    document.getElementById('reservation_date').value = convertToBuddhistDate(todayThailand);

    // ฟังก์ชันเพื่ออัปเดตจำนวนผู้ร่วมเดินทาง
    function updateCompanionCount() {
        var companionText = document.getElementById('companion_name').value;
        // แยกชื่อด้วยทั้งการขึ้นบรรทัดใหม่และเครื่องหมายจุลภาค จากนั้นกรองค่าที่ว่างออก
        var names = companionText.split(/\n|,\s*/).filter(function (name) {
            return name.trim().length > 0;
        });
        document.getElementById('sum_companion').value = names.length || 0; // ตั้งค่าเป็น 0 หากไม่มีชื่อ
    }

    // แนบฟังก์ชัน `updateCompanionCount` กับเหตุการณ์ `input` ของพื้นที่กรอกข้อมูล
    document.getElementById('companion_name').addEventListener('input', updateCompanionCount);

    // ตั้งค่าเริ่มต้น `sum_companion` เป็น 0 เมื่อหน้าโหลด
    updateCompanionCount();
});






 