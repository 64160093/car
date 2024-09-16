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







 