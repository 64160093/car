<?php

namespace App\Http\Controllers;

use App\Models\ReqDocument;
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
        $document->save();

        // ตรวจสอบ division_id ของผู้ใช้ที่ส่งฟอร์ม
        $user = auth()->user();
        $reviewers = [];

        // กำหนดเงื่อนไขการส่งฟอร์มไปหาผู้ใช้ที่มี role_id ที่กำหนด
        if ($user->division_id == 1) {
            $reviewers = User::where('role_id', 4)->get();
        } elseif ($user->division_id == 3) {
            $reviewers = User::where('role_id', 6)->get();
        } elseif ($user->division_id == 4) {
            $reviewers = User::where('role_id', 7)->get();
        } elseif ($user->division_id == 5) {
            $reviewers = User::where('role_id', 8)->get();
        } elseif ($user->division_id == 6) {
            $reviewers = User::where('role_id', 9)->get();
        } elseif ($user->division_id == 7) {
            $reviewers = User::where('role_id', 10)->get();
        }
        // เชื่อมโยงผู้ตรวจสอบกับเอกสาร
        foreach ($reviewers as $reviewer) {
            $document->reqDocumentUsers()->attach($reviewer->id);
        }

        return redirect()->route('documents.index')->with('success', 'ฟอร์มถูกส่งแล้ว');
    }

    // ฟังก์ชันแสดงหน้าตรวจสอบ (reviewForm)
    public function reviewForm()
    {
        $user = auth()->user();
        $documents = collect();

        // ตรวจสอบว่า user มี role_id == 4
        if ($user->role_id == 4) {
            // ดึงเอกสารทั้งหมดที่ผู้ใช้มีการเชื่อมโยง
            $documents = ReqDocument::with('reqDocumentUsers')->whereHas('reqDocumentUsers', function ($query) use ($user) {
                $query->where('user_id', $user->id); 
            })->orderBy('created_at', 'desc')->get();
        }

        return view('reviewform', compact('documents'));
    }




}