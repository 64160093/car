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
    public function index(Request $request)
    {
        // รับค่าจาก request
        $viewType = $request->input('view_type', 'month'); // 'month' หรือ 'quarter' หรือ 'year'
        $month = $request->input('month', date('n')); // month in number (1-12)
        $selectedYear = $request->input('year', date('Y')); // เริ่มต้นที่ปีปัจจุบัน
        $quarter = $request->input('quarter', ceil($month / 3)); // คำนวณไตรมาสจากเดือนปัจจุบัน

        // ดึงจำนวนรถและผู้ใช้จากฐานข้อมูล
        $vehicleCount = Vehicle::count();
        $userCount = User::count();

        // กำหนดตัวแปรสำหรับข้อมูลกราฟ
        $labels = [];
        $data = [];
        $divisionLabels = [];
        $divisionData = [];
        $workLabels = [];
        $workData = [];
        $carTypeLabels = [];
        $carTypeData = [];

        // ดึงปีที่มีข้อมูลจาก ReqDocument
        $yearsWithData = ReqDocument::selectRaw('YEAR(start_date) as year')
            ->groupBy('year')
            ->orderBy('year', 'desc') // เรียงจากปีล่าสุด
            ->pluck('year'); // ดึงเฉพาะปีเป็น Collection

        if ($viewType === 'month') {
            // ดึงข้อมูลสำหรับรายเดือน
            $requestCounts = ReqDocumentUser::selectRaw('user_id, COUNT(*) as count')
                ->join('req_document', 'req_document.document_id', '=', 'req_document_user.req_document_id')
                ->whereYear('req_document.start_date', $selectedYear) // กรองตามปี
                ->whereMonth('req_document.start_date', $month) // กรองตามเดือน
                ->groupBy('user_id')
                ->get();

            foreach ($requestCounts as $requestCount) {
                $user = User::find($requestCount->user_id);
                $labels[] = $user ? $user->name : 'Unknown User'; // ตรวจสอบเพื่อหลีกเลี่ยง error
                $data[] = $requestCount->count;
            }

            // ดึงข้อมูลตามส่วนงาน
            $divisionRequestCounts = ReqDocumentUser::selectRaw('division_id, COUNT(*) as count')
                ->join('req_document', 'req_document.document_id', '=', 'req_document_user.req_document_id')
                ->whereYear('req_document.start_date', $selectedYear)
                ->whereMonth('req_document.start_date', $month)
                ->groupBy('division_id')
                ->get();

            foreach ($divisionRequestCounts as $requestCount) {
                $division = Division::find($requestCount->division_id);
                $divisionLabels[] = $division ? $division->division_name : 'Unknown Division';
                $divisionData[] = $requestCount->count;
            }

            // ดึงข้อมูลตามประเภทงาน
            $workRequestCounts = ReqDocumentUser::selectRaw('work_id, COUNT(*) as count')
                ->join('req_document', 'req_document.document_id', '=', 'req_document_user.req_document_id')
                ->whereYear('req_document.start_date', $selectedYear)
                ->whereMonth('req_document.start_date', $month)
                ->groupBy('work_id')
                ->get();

            foreach ($workRequestCounts as $requestCount) {
                $workType = WorkType::find($requestCount->work_id);
                $workLabels[] = $workType ? $workType->work_name : 'Unknown Work';
                $workData[] = $requestCount->count;
            }

            // ดึงข้อมูลตามประเภทของรถ
            $carTypeRequestCounts = ReqDocument::selectRaw('car_type, COUNT(*) as count')
                ->whereYear('start_date', $selectedYear)
                ->whereMonth('start_date', $month)
                ->groupBy('car_type')
                ->get();

            foreach ($carTypeRequestCounts as $requestCount) {
                $carTypeLabels[] = $requestCount->car_type ? $requestCount->car_type : 'Unknown Car Type';
                $carTypeData[] = $requestCount->count;
            }

        } elseif ($viewType === 'quarter') {
            // ดึงข้อมูลสำหรับรายไตรมาส
            $startMonth = ($quarter - 1) * 3 + 1; // เดือนเริ่มต้นของไตรมาส
            $endMonth = $quarter * 3; // เดือนสิ้นสุดของไตรมาส

            $requestCounts = ReqDocumentUser::selectRaw('user_id, COUNT(*) as count')
                ->join('req_document', 'req_document.document_id', '=', 'req_document_user.req_document_id')
                ->whereYear('req_document.start_date', $selectedYear)
                ->whereBetween('req_document.start_date', ["{$selectedYear}-{$startMonth}-01", "{$selectedYear}-{$endMonth}-31"]) // กรองตามช่วงเวลา
                ->groupBy('user_id')
                ->get();

            foreach ($requestCounts as $requestCount) {
                $user = User::find($requestCount->user_id);
                $labels[] = $user ? $user->name : 'Unknown User';
                $data[] = $requestCount->count;
            }

            // ดึงข้อมูลตามส่วนงาน
            $divisionRequestCounts = ReqDocumentUser::selectRaw('division_id, COUNT(*) as count')
                ->join('req_document', 'req_document.document_id', '=', 'req_document_user.req_document_id')
                ->whereYear('req_document.start_date', $selectedYear)
                ->whereBetween('req_document.start_date', ["{$selectedYear}-{$startMonth}-01", "{$selectedYear}-{$endMonth}-31"])
                ->groupBy('division_id')
                ->get();

            foreach ($divisionRequestCounts as $requestCount) {
                $division = Division::find($requestCount->division_id);
                $divisionLabels[] = $division ? $division->division_name : 'Unknown Division';
                $divisionData[] = $requestCount->count;
            }

            // ดึงข้อมูลตามประเภทงาน
            $workRequestCounts = ReqDocumentUser::selectRaw('work_id, COUNT(*) as count')
                ->join('req_document', 'req_document.document_id', '=', 'req_document_user.req_document_id')
                ->whereYear('req_document.start_date', $selectedYear)
                ->whereBetween('req_document.start_date', ["{$selectedYear}-{$startMonth}-01", "{$selectedYear}-{$endMonth}-31"])
                ->groupBy('work_id')
                ->get();

            foreach ($workRequestCounts as $requestCount) {
                $workType = WorkType::find($requestCount->work_id);
                $workLabels[] = $workType ? $workType->work_name : 'Unknown Work';
                $workData[] = $requestCount->count;
            }

            // ดึงข้อมูลตามประเภทของรถ
            $carTypeRequestCounts = ReqDocument::selectRaw('car_type, COUNT(*) as count')
                ->whereYear('start_date', $selectedYear)
                ->whereBetween('start_date', ["{$selectedYear}-{$startMonth}-01", "{$selectedYear}-{$endMonth}-31"])
                ->groupBy('car_type')
                ->get();

            foreach ($carTypeRequestCounts as $requestCount) {
                $carTypeLabels[] = $requestCount->car_type ? $requestCount->car_type : 'Unknown Car Type';
                $carTypeData[] = $requestCount->count;
            }

        } elseif ($viewType === 'year') {
            // ดึงข้อมูลสำหรับทั้งปี
            $requestCounts = ReqDocumentUser::selectRaw('user_id, COUNT(*) as count')
                ->join('req_document', 'req_document.document_id', '=', 'req_document_user.req_document_id')
                ->whereYear('req_document.start_date', $selectedYear)
                ->groupBy('user_id')
                ->get();

            foreach ($requestCounts as $requestCount) {
                $user = User::find($requestCount->user_id);
                $labels[] = $user ? $user->name : 'Unknown User';
                $data[] = $requestCount->count;
            }

            // ดึงข้อมูลตามส่วนงาน
            $divisionRequestCounts = ReqDocumentUser::selectRaw('division_id, COUNT(*) as count')
                ->join('req_document', 'req_document.document_id', '=', 'req_document_user.req_document_id')
                ->whereYear('req_document.start_date', $selectedYear)
                ->groupBy('division_id')
                ->get();

            foreach ($divisionRequestCounts as $requestCount) {
                $division = Division::find($requestCount->division_id);
                $divisionLabels[] = $division ? $division->division_name : 'Unknown Division';
                $divisionData[] = $requestCount->count;
            }

            // ดึงข้อมูลตามประเภทงาน
            $workRequestCounts = ReqDocumentUser::selectRaw('work_id, COUNT(*) as count')
                ->join('req_document', 'req_document.document_id', '=', 'req_document_user.req_document_id')
                ->whereYear('req_document.start_date', $selectedYear)
                ->groupBy('work_id')
                ->get();

            foreach ($workRequestCounts as $requestCount) {
                $workType = WorkType::find($requestCount->work_id);
                $workLabels[] = $workType ? $workType->work_name : 'Unknown Work';
                $workData[] = $requestCount->count;
            }

            // ดึงข้อมูลตามประเภทของรถ
            $carTypeRequestCounts = ReqDocument::selectRaw('car_type, COUNT(*) as count')
                ->whereYear('start_date', $selectedYear)
                ->groupBy('car_type')
                ->get();

            foreach ($carTypeRequestCounts as $requestCount) {
                $carTypeLabels[] = $requestCount->car_type ? $requestCount->car_type : 'Unknown Car Type';
                $carTypeData[] = $requestCount->count;
            }
        }

        // ส่งข้อมูลไปยัง view
        return view('adminDashboard', compact(
            'vehicleCount',
            'userCount',
            'labels',
            'data',
            'divisionLabels',
            'divisionData',
            'workLabels',
            'workData',
            'carTypeLabels',
            'carTypeData',
            'viewType',
            'month',
            'selectedYear',
            'quarter', // เพิ่มตัวแปร quarter ที่ส่งไปยัง view
            'yearsWithData' // เพิ่มตัวแปรที่เก็บปีที่มีข้อมูล
        ));
    }
}