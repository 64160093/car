<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DriverScheduleController extends Controller
{
    public function index()
    {
        // ตรวจสอบว่าผู้ใช้มี role_id เท่ากับ 11 หรือไม่
        if (auth()->user()->role_id != 11) {
            return redirect()->route('home')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        // ดึงข้อมูลที่ต้องการแสดงผลในหน้าแผนงาน
        $schedules = []; // ดึงข้อมูลแผนงานจากฐานข้อมูลหากต้องการ

        return view('driver.schedule', compact('schedules'));
    }
}