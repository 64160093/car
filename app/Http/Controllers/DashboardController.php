<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle; // ใช้โมเดล Vehicle สำหรับดึงข้อมูลรถ
use App\Models\User;    // ใช้โมเดล User สำหรับดึงข้อมูลผู้ใช้
use App\Models\ReqDocument; // ใช้โมเดล ReqDocument สำหรับดึงข้อมูลการขออนุญาต
use App\Models\ReqDocumentUser; // นำเข้าโมเดลตารางกลาง
use App\Models\Division; // ใช้โมเดล Division สำหรับดึงข้อมูลส่วนงาน
use App\Models\WorkType; // ใช้โมเดล WorkType สำหรับดึงข้อมูลประเภทงาน

class DashboardController extends Controller
{
    public function index()
    {
        // ดึงจำนวนรถและผู้ใช้จากฐานข้อมูล
        $vehicleCount = Vehicle::count();
        $userCount = User::count();

        // ดึงจำนวนการขออนุญาตที่แต่ละบุคลากรมีการขออนุญาต
        $requestCounts = ReqDocumentUser::selectRaw('user_id, COUNT(*) as count')
            ->groupBy('user_id')
            ->get();

        // แปลงข้อมูลให้อยู่ในรูปแบบที่เหมาะสมสำหรับกราฟ
        $labels = [];
        $data = [];

        foreach ($requestCounts as $requestCount) {
            $user = User::find($requestCount->user_id);
            $labels[] = $user ? $user->name : 'Unknown User'; // ตรวจสอบเพื่อหลีกเลี่ยง error
            $data[] = $requestCount->count;
        }

        // ดึงจำนวนการขออนุญาตตามส่วนงาน
        $divisionRequestCounts = ReqDocumentUser::selectRaw('division_id, COUNT(*) as count')
            ->groupBy('division_id')
            ->get();

        // แปลงข้อมูลสำหรับกราฟตามส่วนงาน
        $divisionLabels = [];
        $divisionData = [];

        foreach ($divisionRequestCounts as $requestCount) {
            $division = Division::find($requestCount->division_id);
            $divisionLabels[] = $division ? $division->division_name : 'Unknown Division'; // ตรวจสอบเพื่อหลีกเลี่ยง error
            $divisionData[] = $requestCount->count;
        }

        // ดึงจำนวนการขออนุญาตตามประเภทงาน
        $workRequestCounts = ReqDocument::selectRaw('work_id, COUNT(*) as count')
            ->groupBy('work_id')
            ->get();

        // แปลงข้อมูลสำหรับกราฟตามประเภทงาน
        $workLabels = [];
        $workData = [];

        foreach ($workRequestCounts as $requestCount) {
            $workType = WorkType::find($requestCount->work_id);
            $workLabels[] = $workType ? $workType->work_name : 'Unknown Work'; // ตรวจสอบเพื่อหลีกเลี่ยง error
            $workData[] = $requestCount->count;
        }

        // ดึงจำนวนการขออนุญาตตามประเภทของรถ
        $carTypeRequestCounts = ReqDocument::selectRaw('car_type, COUNT(*) as count')
            ->groupBy('car_type')
            ->get();

        // แปลงข้อมูลสำหรับกราฟตามประเภทของรถ
        $carTypeLabels = [];
        $carTypeData = [];

        foreach ($carTypeRequestCounts as $requestCount) {
            $carTypeLabels[] = $requestCount->car_type ? $requestCount->car_type : 'Unknown Car Type'; // ตรวจสอบเพื่อหลีกเลี่ยง error
            $carTypeData[] = $requestCount->count;
        }

        // ส่งข้อมูลไปยัง view
        return view('adminDashboard', compact('vehicleCount', 'userCount', 'labels', 'data', 'divisionLabels', 'divisionData', 'workLabels', 'workData', 'carTypeLabels', 'carTypeData'));
    }
}
