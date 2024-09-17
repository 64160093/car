<?php

namespace App\Http\Controllers;

use App\Models\Document; // เรียกใช้ Model Document
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
            $documents = Document::where('id', $user->id)->get();
        }

        return view('documents.index', compact('documents'));
    }
}
