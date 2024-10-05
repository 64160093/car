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
        return view('reqdocument', compact('provinces', 'amphoe', 'district', 'work_type', 'user'));
    }


    public function store(Request $request)
    {
        // ตรวจสอบข้อมูล
        $request->validate([
            'companion_name' => 'required|string|max:255',
            'objective' => 'required|string|max:255',
            'reservation_date' => 'required|date',
            'location' => 'required|string|max:255',
            'car_pickup' => 'required|string|max:255',
            'related_project' => 'nullable|mimes:pdf|max:2048', // ตรวจสอบไฟล์ที่แนบ
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'sum_companion' => 'required|integer',
            'car_type' => 'required|string|max:255',
            'provinces_id' => 'required|exists:provinces,provinces_id',
            'amphoe_id' => 'required|exists:amphoe,amphoe_id',
            'district_id' => 'required|exists:district,district_id',
            'work_id' => 'required|exists:work_type,work_id',
        ]);

        // จัดการการอัปโหลดไฟล์
        $filePath = null;
        if ($request->hasFile('related_project')) {
            $filePath = $request->file('related_project')->store('projects');
        }

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

        return redirect('/document-history')->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
    }


    public function __construct()
    {
        $this->middleware('auth');
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

}