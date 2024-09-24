<?php

namespace App\Http\Controllers;

use App\Models\ReqDocument;
use App\Models\ReqDocumentUser;
use App\Models\Document; // เรียกใช้ Model Document
use App\Models\User;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->is_admin) {
            // แสดงรายการทั้งหมดสำหรับ admin
            $documents = Document::all();
        } else {
            // แสดงเฉพาะรายการที่ผู้ใช้คนนั้นเป็นผู้ส่ง
            // ตรวจสอบให้แน่ใจว่าใช้ user_id ที่ถูกต้อง
            $documents = Document::where('user_id', $user->id)->get(); // ใช้ user_id แทน id
        }

        return view('documents.index', compact('documents'));
    }


    public function store(Request $request)
    {
        // Validate and save the document data
        $document = new Document();
        $document->fill($request->all());
        $document->user_id = auth()->id(); // บันทึก user_id ของผู้ส่ง
        $document->save();

        // ตรวจสอบ division_id ของผู้ใช้ที่ส่งฟอร์ม
        $user = auth()->user();
        $reviewers = [];

        // กำหนดเงื่อนไขการส่งฟอร์มไปหาผู้ใช้ที่มี role_id ที่กำหนด
        if ($user->division_id == 1) {
            $reviewers = User::where('role_id', 4)->pluck('id')->toArray();
        } elseif ($user->division_id == 3) {
            $reviewers = User::where('role_id', 6)->pluck('id')->toArray();
        } elseif ($user->division_id == 4) {
            $reviewers = User::where('role_id', 7)->pluck('id')->toArray();
        } elseif ($user->division_id == 5) {
            $reviewers = User::where('role_id', 8)->pluck('id')->toArray();
        } elseif ($user->division_id == 6) {
            $reviewers = User::where('role_id', 9)->pluck('id')->toArray();
        } elseif ($user->division_id == 7) {
            $reviewers = User::where('role_id', 10)->pluck('id')->toArray();
        }

        // เชื่อมโยงผู้ตรวจสอบกับเอกสาร โดยบันทึกลงใน req_document_user
        foreach ($reviewers as $reviewer) {
            \DB::table('req_document_user')->insert([
                'req_document_id' => $document->id,
                'user_id' => $reviewer,
            ]);
        }

        return redirect()->route('documents.index')->with('success', 'ฟอร์มถูกส่งแล้ว');
    }

    // ฟังก์ชันแสดงหน้าตรวจสอบ (reviewForm)
    public function reviewForm()
    {
        $user = auth()->user();
        
        // ตรวจสอบว่าเป็นผู้ตรวจสอบหรือไม่ โดยดูจาก role_id
        $isReviewer = in_array($user->role_id, [4, 6, 7, 8, 9, 10]);

        if ($isReviewer) {
            // ผู้ใช้เป็นผู้ตรวจสอบ แสดงเฉพาะฟอร์มที่ต้องตรวจสอบตาม division_id
            $documents = ReqDocument::whereHas('reqDocumentUsers', function ($query) use ($user) {
                $query->where('req_document_user.user_id', '!=', $user->id) // ไม่แสดงฟอร์มที่ผู้ใช้ส่งเอง
                    ->where(function ($query) use ($user) {
                        // เงื่อนไขการแสดงฟอร์มตาม division_id และ role_id
                        if ($user->role_id == 4) {
                            $query->where('req_document_user.division_id', 1);
                        } elseif ($user->role_id == 6) {
                            $query->where('req_document_user.division_id', 3);
                        } elseif ($user->role_id == 7) {
                            $query->where('req_document_user.division_id', 4);
                        } elseif ($user->role_id == 8) {
                            $query->where('req_document_user.division_id', 5);
                        } elseif ($user->role_id == 9) {
                            $query->where('req_document_user.division_id', 6);
                        } elseif ($user->role_id == 10) {
                            $query->where('req_document_user.division_id', 7);
                        }
                    });
            })->orderBy('created_at', 'desc')->get();
        } else {
            // ผู้ใช้เป็นผู้ส่งฟอร์มเอง แสดงเฉพาะฟอร์มของตัวเอง
            $documents = ReqDocument::whereHas('reqDocumentUsers', function ($query) use ($user) {
                $query->where('req_document_user.user_id', $user->id); // ดึงฟอร์มที่ผู้ใช้เป็นคนส่ง
            })->orderBy('created_at', 'desc')->get();
        }

        return view('reviewform', compact('documents'));
    }





}