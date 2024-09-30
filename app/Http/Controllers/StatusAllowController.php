<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\StatusAllow;
class StatusAllowController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        // รับข้อมูล status จากฐานข้อมูล
        $status = StatusAllow::findOrFail($id);
        // อัปเดตสถานะที่ส่งมาจากฟอร์ม
        $status->update([
            'allow_department' => $request->input('allow_department'),
            'allow_division' => $request->input('allow_division'),
            'allow_opcar' => $request->input('allow_opcar'),
            'allow_officer' => $request->input('allow_officer'),
            'allow_director' => $request->input('allow_director'),
            'not_allowed' => $request->input('not_allowed'),
            'cancel_allowed' => $request->input('cancel_allowed'),
            'status_driver' => $request->input('status_driver'),
        
        ]);
        return redirect()->back()->with('success', 'Status updated successfully.');
    }
}