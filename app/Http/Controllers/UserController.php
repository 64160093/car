<?php
// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Division;
use App\Models\Department;
use App\Models\Position;
use App\Models\Role;

class UserController extends Controller
{
    public function edit() {
        $user = Auth::user();
        $divisions = Division::all();
        $departments = Department::all();
        $positions = Position::all(); // ดึงข้อมูลตำแหน่งทั้งหมด
        $roles = Role::all();

    
        return view('profile.edit', compact('user', 'divisions', 'departments', 'positions', 'roles' ));
    }
    
    //'

    public function update(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'phonenumber' => 'nullable|digits:10|regex:/^[0-9]+$/', // เบอร์ไม่เกิน 10
            'division_id' => 'nullable|exists:divisions,division_id', // ตรวจสอบ division_id
            'department_id' => 'nullable|exists:departments,department_id', // ตรวจสอบ department_id
            'position_id' => 'nullable|exists:positions,position_id',
            'role_id' => 'nullable|exists:roles,role_id',
            'signature_name' => 'nullable|image|mimes:png|max:1024|dimensions:width=530,height=120', // png
        ]);

        // if ($request->hasFile('signature_name')) {
        //     // ลบรูปภาพเก่าถ้ามี
        //     if ($user->signature_name) {
        //         Storage::delete('public/' . $user->signature_name);
        //     }

        //     // อัปโหลดรูปภาพใหม่
        //     $path = $request->file('signature_name')->store('signatures', 'public');
        //     $validatedData['signature_name'] = $path;
        // }

        // ทำให้เข้าผ่าน url ไม่ได้้
            if ($request->hasFile('signature_name')) {
                // ลบรูปภาพเก่าถ้ามี
                if ($user->signature_name) {
                    Storage::delete('signatures/' . $user->signature_name);
                }

                // สร้างชื่อไฟล์ใหม่ที่มี ID ของผู้ใช้
                $filename = $user->id . '_signature.' . $request->file('signature_name')->getClientOriginalExtension();

                // อัปโหลดรูปภาพใหม่ไปที่โฟลเดอร์ storage/app/signatures โดยใช้ชื่อไฟล์ที่กำหนด
                $path = $request->file('signature_name')->storeAs('signatures', $filename);

                $validatedData['signature_name'] = $filename;
            }
            
        // อัพเดตข้อมูลผู้ใช้
        $user->update($validatedData);

        return redirect()->route('profile.edit')->with('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
    }
}