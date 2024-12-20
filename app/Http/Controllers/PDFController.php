<?php
  
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ReqDocument;
use App\Models\ReqDocumentUser ;
use App\Models\ReportFormance;

use PDF;
    
class PDFController extends Controller
{
    /**
     * บันทึกคำร้อง
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePDF(Request $request)
    {
        $imagePathPublic = public_path('images/buu-logo.png');
        $imageCancel = public_path('images/cancel.png');
        $id = $request->input('id');
        $documents = ReqDocument::with(['reqDocumentUsers', 'users', 'province', 'vehicle', 'carmanUser', 'DivisionAllowBy'])
                                 ->findOrFail($id);
    
        $data = [
            'title' => 'Document Report',
            'documents' => $documents,
            'imagePathPublic' => $imagePathPublic, // ส่งเส้นทางของภาพใน public
            'imageCancel' => $imageCancel,
        ];
    
        $pdf = PDF::loadView('myPDF', $data);
        return $pdf->stream('document_report.pdf');
    }
    



        /**
     * รายงานคนขับรถ
     *
     * 
     */
    public function generateReportPDF(Request $request)
    {
        $id = $request->input('id');
        $report = ReportFormance::with(['vehicle', 'province', 'carmanUser']) // ปรับให้เข้ากับโครงสร้างโมเดลของคุณ
            ->findOrFail($id);

        $documents = ReqDocument::with(['reqDocumentUsers', 'users', 'province', 'vehicle','carmanUser','DivisionAllowBy'])
            ->findOrFail($report->req_document_id); // ใช้ req_document_id จาก $report

        $data = [
            'report' => $report,
            'documents' => $documents,  // ส่งข้อมูล $documents ไปที่ view
        ];

        $pdf = PDF::loadView('driver.PDFreport', $data);

        return $pdf->stream('report_' . $report->report_id . '.pdf');
    }
    

}
