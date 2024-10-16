<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReqDocument;
use App\Models\ReportFormance;
use App\Models\User;

class ReportDocumentController extends Controller
{
    public function index(Request $request)
    {
        $user = User::all();
        $id = $request->input('id'); // รับค่า id จาก request

        // ดึงข้อมูลเอกสารพร้อมกับความสัมพันธ์ที่เกี่ยวข้อง
        $documents = ReqDocument::with(['reqDocumentUsers', 'users', 'province', 'vehicle'])
            ->findOrFail($id);

        return view('driver.reportdocument', compact('documents', 'user'));
    }

    public function store(Request $request)
    {
        // ตรวจสอบว่ามีการส่ง document_id มาหรือไม่
        if (!$request->has('document_id')) {
            return redirect()->back()->withErrors(['document_id' => 'Document ID is required.']);
        }

        $request->validate([
            'document_id' => 'required|exists:req_document,document_id',
            'stime' => 'required',
            'etime' => 'required',
            'skilo_num' => 'required|digits:6',
            'ekilo_num' => 'required|digits:6',
            'total_companion' => 'required|integer|min:1',
            'gasoline_cost' => 'nullable|numeric',
            'expressway_toll' => 'nullable|numeric',
            'parking_fee' => 'nullable|numeric',
            'another_cost' => 'nullable|numeric',
            'performance_isgood' => 'required|in:Y,N',
            'comment_issue' => 'nullable|string',
        ]);

        // คำนวณค่าใช้จ่ายรวม
        $total_cost = ($request->gasoline_cost ?? 0) + ($request->expressway_toll ?? 0)
            + ($request->parking_fee ?? 0) + ($request->another_cost ?? 0);

        // บันทึกข้อมูลลงในฐานข้อมูล
        $report = ReportFormance::create([
            'req_document_id' => $request->document_id, // ใช้ req_document_id แทน document_id
            'stime' => $request->stime,
            'etime' => $request->etime,
            'skilo_num' => $request->skilo_num,
            'ekilo_num' => $request->ekilo_num,
            'total_companion' => $request->total_companion,
            'gasoline_cost' => $request->gasoline_cost,
            'expressway_toll' => $request->expressway_toll,
            'parking_fee' => $request->parking_fee,
            'another_cost' => $request->another_cost,
            'total_cost' => $total_cost,
            'performance_isgood' => $request->performance_isgood,
            'comment_issue' => ($request->performance_isgood === 'N') ? $request->comment_issue : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Redirect to the show view after storing the report
        return redirect()->route('reportdoc.show', ['id' => $report->report_id])
            ->with('success', 'เพิ่มข้อมูลรายงานสำเร็จ!');
    }
    public function show($id)
    {
        // ดึงข้อมูลรายงานตาม ID
        $report = ReportFormance::findOrFail($id); // ใช้ findOrFail เพื่อดึงข้อมูลหรือแสดง 404 หากไม่พบ

        return view('driver.showrepdoc', compact('report')); // ส่งข้อมูลไปยัง view ชื่อ reportdetails
    }


}
