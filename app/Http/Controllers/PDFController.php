<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ReqDocument;
use App\Models\ReqDocumentUser;
use App\Models\ReportFormance;

use PDF;

class PDFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePDF(Request $request)
    {
        // รับค่า id จาก request
        $id = $request->input('id');

        // ดึงข้อมูลเอกสารที่เกี่ยวข้องด้วย findOrFail และความสัมพันธ์ต่างๆ
        $documents = ReqDocument::with(['reqDocumentUsers', 'users', 'province', 'vehicle', 'carmanUser', 'DivisionAllowBy'])
            ->findOrFail($id);

        $data = [
            'title' => 'Document Report',
            'documents' => $documents  // ส่งข้อมูล $documents ไปที่ view
        ];

        // สร้าง PDF จาก view 'myPDF' โดยส่ง $data
        $pdf = PDF::loadView('myPDF', $data);

        // แสดง PDF ในเบราว์เซอร์
        return $pdf->stream('document_report.pdf');
    }
    public function generateReportPDF(Request $request)
    {
        // รับค่า id จาก request
        $id = $request->input('id');

        // ดึงข้อมูลรายงานที่เกี่ยวข้องด้วย findOrFail และความสัมพันธ์ต่างๆ
        $report = ReportFormance::with(['vehicle', 'province', 'carmanUser']) // ปรับให้เข้ากับโครงสร้างโมเดลของคุณ
            ->findOrFail($id);

        // ดึงข้อมูล ReqDocument ที่เกี่ยวข้องกับรายงานนี้
        $documents = ReqDocument::with(['reqDocumentUsers', 'users', 'province', 'vehicle', 'carmanUser', 'DivisionAllowBy'])
            ->findOrFail($report->req_document_id); // ใช้ req_document_id จาก $report

        $data = [
            'report' => $report,
            'documents' => $documents,  // ส่งข้อมูล $documents ไปที่ view
        ];

        // สร้าง PDF จาก view 'driver.showrepdoc' โดยส่ง $data
        $pdf = PDF::loadView('driver.showrepdoc', $data);

        // แสดง PDF ในเบราว์เซอร์
        return $pdf->stream('report_' . $report->report_id . '.pdf');
    }
}
