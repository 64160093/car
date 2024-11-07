<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ReqDocument;
use App\Models\Province;
use App\Models\Amphoe;
use App\Models\District;
use App\Models\WorkType;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\CarIcon;


class ReqDocumentController extends Controller
{
    public function index()
    {
        //
    }


    public function create()
    {
        $user = User::all();
        $provinces = Province::all();
        $amphoe = Amphoe::all();
        $district = District::all();
        $work_type = WorkType::all();
        $vehicles = Vehicle::all();
        $caricon = CarIcon::all();

        return view('reqdocument', compact('provinces', 'amphoe', 'district', 'work_type', 'user', 'vehicles', 'caricon'));
    }


    public function store(Request $request)
    {
        // ตรวจสอบข้อมูล
        // กำหนดกฎการตรวจสอบข้อมูล
        $rules = [
            'companion_name' => 'required|string|max:255',
            'objective' => 'required|string|max:255',
            'reservation_date' => 'required|date',
            'location' => 'required|string|max:255',
            'car_pickup' => 'required|string|max:255',
            'related_project' => 'nullable|mimes:pdf|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'sum_companion' => 'required|integer',
            'provinces_id' => 'required|exists:provinces,provinces_id',
            'amphoe_id' => 'required|exists:amphoe,amphoe_id',
            'district_id' => 'required|exists:district,district_id',
            'work_id' => 'required|exists:work_type,work_id',
            'car_id' => 'nullable|exists:vehicles,car_id',
            'carman' => 'nullable|exists:user,id',
            'car_controller' => 'required|exists:users,id',
        ];
        // ตรวจสอบว่าติ๊ก "รถเช่า" หรือไม่
        if ($request->input('car_rent')) {
            // ถ้าติ๊ก "รถเช่า" จะบันทึก car_type เป็น "รถเช่า"
            $request->merge(['car_type' => 'รถเช่า']);
            // ไม่บังคับให้กรอก car_type
        } else {
            // ถ้าไม่ติ๊ก ให้บังคับกรอก car_type
            $rules['car_type'] = 'required|string|max:255';
        }
        // ทำการ validate
        $request->validate($rules);

        $companions = explode(',', $request->input('companions_hidden'));



        // ตรวจสอบการจองทับซ้อน
        $existingBooking = ReqDocument::where('car_id', $request->input('car_id'))
            ->where('car_type', $request->input('car_type'))
            ->where(function ($query) use ($request) {
                // ตรวจสอบการจองในวันเดียวกัน
                $query->where('start_date', $request->start_date)
                    ->where(function ($timeQuery) use ($request) {
                    $timeQuery->where(function ($subTimeQuery) use ($request) {
                        // ตรวจสอบว่าเวลาที่จองใหม่ซ้อนทับกับเวลาที่ถูกจอง
                        $subTimeQuery->whereBetween('start_time', [$request->start_time, $request->end_time])
                            ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                            ->orWhere(function ($nestedTimeQuery) use ($request) {
                            $nestedTimeQuery->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
                        });
                    });
                });
            })
            ->orWhere(function ($crossDayQuery) use ($request) {
                // ตรวจสอบการจองข้ามวัน
                $crossDayQuery->where('start_date', '<=', $request->end_date)
                    ->where('end_date', '>=', $request->start_date)
                    ->where(function ($timeQuery) use ($request) {
                    $timeQuery->whereBetween('start_time', [$request->start_time, $request->end_time])
                        ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                        ->orWhere(function ($subTimeQuery) use ($request) {
                            // ตรวจสอบการซ้อนทับเวลา
                            $subTimeQuery->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
                        });
                });
            })
            ->exists();

        // ป้องกันการจองซ้อนทับ
        if ($existingBooking) {
            return back()->withErrors(['start_date' => 'วันเวลาที่คุณเลือกถูกจองไปแล้ว'])->withInput();
        }



        // จัดการการอัปโหลดไฟล์
        $filePath = null;
        if ($request->hasFile('related_project')) {
            $filePath = $request->file('related_project')->store('projects', 'public');
        }

        // ตรวจสอบ role_id ว่าเป็น 12 หรือไม่ เพื่อบันทึกค่า car_id
        $carId = auth()->user()->role_id == 12 ? $request->input('car_id') : null;
        $carMan = auth()->user()->role_id == 12 ? $request->input('carman') : null;


        // บันทึกข้อมูลลงในตาราง req_document
        $document = ReqDocument::create([
            'companion_name' => $request->companion_name,
            'objective' => $request->objective,
            'related_project' => $filePath ?? null,
            'location' => $request->location,
            'car_pickup' => $request->car_pickup,
            'reservation_date' => $request->reservation_date,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'sum_companion' => $request->sum_companion,
            'car_type' => $request->car_type,
            'provinces_id' => $request->provinces_id,
            'amphoe_id' => $request->amphoe_id,
            'district_id' => $request->district_id,
            'work_id' => $request->work_id,
            'car_id' => $carId, // บันทึก car_id เฉพาะ role_id == 12 เท่านั้น
            'carman' => $carMan,
            'car_controller' => $request->input('car_controller'),
        ]);


        // บันทึกความสัมพันธ์ระหว่างผู้ใช้และเอกสารในตาราง req_document_user
        $document->users()->attach(Auth::user()->id, [
            'name' => Auth::user()->name,
            'lname' => Auth::user()->lname,
            'signature_name' => Auth::user()->signature_name,
            'division_id' => Auth::user()->division_id,
            'department_id' => Auth::user()->department_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ตรวจสอบ role_id ว่าเป็น 12 หรือไม่ เพื่อบันทึกค่า car_id
// $carId = auth()->user()->role_id == 12 ? $request->input('car_id') : null;
// $carMan = auth()->user()->role_id == 12 ? $request->input('carman') : null;

        // // ตรวจสอบข้อมูลรถจากโมเดล Vehicle
// $vehicle = $carId ? \App\Models\Vehicle::find($carId) : null;
// $carRegNumber = $vehicle ? $vehicle->car_regnumber : null;
// $carProvince = $vehicle ? $vehicle->car_province : null;
// $carCategory = $vehicle ? $vehicle->car_category : null;

        // // ตรวจสอบค่าที่ดึงมา
// dd($carRegNumber, $carProvince, $carCategory);


        // // บันทึกข้อมูลลงในตาราง req_document
// $document = ReqDocument::create([
//     'companion_name' => $request->companion_name,
//     'objective' => $request->objective,
//     'related_project' => $filePath ?? null,
//     'location' => $request->location,
//     'car_pickup' => $request->car_pickup,
//     'reservation_date' => $request->reservation_date,
//     'start_date' => $request->start_date,
//     'end_date' => $request->end_date,
//     'start_time' => $request->start_time,
//     'end_time' => $request->end_time,
//     'sum_companion' => $request->sum_companion,
//     'car_type' => $request->car_type,
//     'provinces_id' => $request->provinces_id,
//     'amphoe_id' => $request->amphoe_id,
//     'district_id' => $request->district_id,
//     'work_id' => $request->work_id,
//     'car_id' => $carId, // บันทึก car_id เฉพาะ role_id == 12 เท่านั้น
//     'carman' => $carMan,
//     'car_controller' => $request->input('car_controller'),
// ]);

        // // บันทึกความสัมพันธ์ระหว่างผู้ใช้และเอกสารในตาราง req_document_user
// $document->users()->attach(Auth::user()->id, [
//     'name' => Auth::user()->name,
//     'lname' => Auth::user()->lname,
//     'signature_name' => Auth::user()->signature_name,
//     'division_id' => Auth::user()->division_id,
//     'department_id' => Auth::user()->department_id,
//     'car_regnumber' => $carRegNumber, // ใช้ค่า car_regnumber จากโมเดล Vehicle
//     'car_province' => $carProvince,   // ใช้ค่า car_province จากโมเดล Vehicle
//     'car_category' => $carCategory,   // ใช้ค่า car_category จากโมเดล Vehicle
//     'created_at' => now(),
//     'updated_at' => now(),
// ]);


        foreach ($companions as $companionId) {
            // ตรวจสอบว่า $companionId เป็นตัวเลขหรือไม่ (ป้องกันข้อผิดพลาดจากข้อมูลที่ไม่ถูกต้อง)
            if (is_numeric($companionId)) {
                $document->companions()->attach($companionId);
            }
        }

        return redirect('/document-history')->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
    }


    public function __construct()
    {
        $this->middleware('auth')->except(['getEvents']);
    }

    public function getAmphoes($provinceId)
    {
        $amphoes = Amphoe::where('provinces_id', $provinceId)->get(['amphoe_id as id', 'name_th as name']);
        return response()->json($amphoes);
    }

    public function getDistricts($amphoeId)
    {
        $districts = District::where('amphoe_id', $amphoeId)->get(['district_id as id', 'name_th as name']);
        return response()->json($districts);
    }



    public function getEvents()
    {
        // ดึงข้อมูลจาก req_document พร้อมข้อมูลจาก req_document_user และ user
        $documents = ReqDocument::select('req_document.document_id', 'req_document.objective', 'start_date', 'end_date', 'start_time', 'end_time', 'req_document.car_type', 'req_document_user.name', 'req_document_user.lname', 'location')
            ->join('req_document_user', 'req_document.document_id', '=', 'req_document_user.req_document_id')
            ->join('users', 'req_document_user.user_id', '=', 'users.id') // เชื่อมกับตาราง users
            ->get()
            ->map(function ($document) {
                return [
                    'id' => $document->document_id,
                    'title' => $document->objective,
                    'start' => $document->start_date . 'T' . $document->start_time, // รวมวันที่และเวลาเริ่ม
                    'end' => $document->end_date . 'T' . $document->end_time,       // รวมวันที่และเวลาสิ้นสุด
                    'backgroundColor' => '#ffc107', // สีพื้นหลัง
                    'requester_name' => $document->name . ' ' . $document->lname, // รวมชื่อและนามสกุลของผู้ขอ
                    'location' => $document->location,  // สถานที่
                    'vehicle_type' => $document->car_type,// ประเภทรถจาก req_document
    
                    'extendedProps' => [
                        'objective' => $document->objective, // เพิ่มข้อมูลวัตถุประสงค์
                    ]
                ];
            });
        // ส่งข้อมูลในรูปแบบ JSON ให้กับ FullCalendar
        return response()->json($documents);
    }



}