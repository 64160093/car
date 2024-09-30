<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vehicle;
use App\Models\CarIcon;
use App\Models\User;
use App\Models\Division;
use App\Models\Department;
use App\Models\Position;
use App\Models\Role;
use Illuminate\Support\Facades\Crypt;
use App\Models\ReqDocument;
use App\Models\ReqDocumentUser;

class AdminController extends Controller
{
    //------------------------- ส่วนเพิ่มรถ -------------------------

    /**
     * Add Vehicle Form
     *
     * 
     */   
    public function storeVehicle(Request $request)
    {
        // Validate the input
        $request->validate([
            'icon_id' => ['required', 'exists:car_icon,icon_id'],
            'car_category' => 'required|string|max:3',                  //varchar(3)
            'car_regnumber' => 'required|integer|digits_between:1,4',   //int(4)
            'car_province' => 'required|string|max:255',       
        ]);

        // Store the data into the database (Assuming a Vehicle model exists)
        \App\Models\Vehicle::create([
            'icon_id' => $request->icon_id, 
            'car_category' => $request->car_category,
            'car_regnumber' => $request->car_regnumber,
            'car_province' => $request->car_province,

        ]);
        
        return redirect()->route('show.vehicles')->with('success', 'เพิ่มข้อมูลรถ สำเร็จ!!!');
    }


    /**
     * "Show" "Change Status" "Delete" Vehicle 
     *
     * 
     */
    public function showVehicles()
    {
        $vehicles = Vehicle::all();
        $car_icons = CarIcon::all();
        $selectedIcons = Vehicle::pluck('icon_id')->toArray();
        $availableCarIcons = CarIcon::whereNotIn('icon_id', $selectedIcons)->get();
 
        // ส่งข้อมูลไปยัง View
        return view('vehicles.index', compact('vehicles', 'availableCarIcons', 'car_icons'));
    }

    public function updateStatus(Request $request, $id)
    {
        $vehicle = Vehicle::find($id);

        if ($vehicle) {
            $vehicle->car_status = $request->input("car_status_$id");   // รับค่าจากฟอร์ม
            // ตรวจสอบว่าหากสถานะเป็น "ไม่พร้อม" ให้บันทึก car_reason
            if ($vehicle->car_status == 'N') {
                $vehicle->car_reason = $request->input("car_reason_$id");
            } else {
                $vehicle->car_reason = null;    // ล้างค่า car_reason ถ้ารถพร้อมใช้งาน
            }
            $vehicle->save();
            
            return redirect()->route('show.vehicles')->with('success', 'อัปเดตสถานะเรียบร้อยแล้ว');
        }

        // แจ้งเตือนหากไม่พบข้อมูล
        return redirect()->route('show.vehicles')->with('error', 'ไม่พบข้อมูลรถ');
    }

    public function destroy($id)
    {
        // ค้นหาข้อมูลรถตาม car_id และทำการลบ
        $vehicle = Vehicle::find($id);
        if ($vehicle) {
            $vehicle->delete();
        }
        return redirect()->route('show.vehicles');
    }


    /**
     *
     *
     * 
     */
    //------------------------- ส่วนแก้ไขข้อมูลผู้ใช้ -------------------------
    public function index()
    {
        // $users = User::all(); 
        $users = User::paginate(10); // ดึงข้อมูลผู้ใช้ 10 รายการต่อหน้า
        $divisions = Division::all();
        $departments = Department::all();
        $positions = Position::all(); 
        $roles = Role::all();
        return view('admin.users.index', compact('users', 'divisions', 'departments', 'positions', 'roles'));
    }


    public function editUser($encryptedId)
    {
        $id = Crypt::decryptString($encryptedId);
        $user = User::findOrFail($id);
        $departments = Department::all();
        $divisions = Division::all();
        $positions = Position::all();
        $roles = Role::all();
        
        return view('admin.users.edit', compact('user', 'divisions', 'departments', 'positions', 'roles'));
    }

    public function updateUser(Request $request, $id)
    {
        $currentUser = Auth::user();
        if ($currentUser->is_admin !== 1) {
            return redirect()->route('admin.users')->with('error', 'คุณไม่มีสิทธิ์ในการแก้ไขข้อมูลผู้ใช้นี้.');
        }

        $validatedData = $request->validate([
            'division_id' => 'required|exists:division,division_id',
            'department_id' => 'nullable|exists:department,department_id|required_if:division_id,2',
            'position_id' => 'required|exists:position,position_id',
            'role_id' => 'required|exists:role,role_id',
        ]);

        $user = User::findOrFail($id);

        // ตรวจค่า division_id และตั้งค่า department_id เป็น null
        if ($validatedData['division_id'] != 2) {
            $user->department_id = null;
        } else {
            $user->department_id = $validatedData['department_id'];
        }

        // อัปเดตค่าอื่น ๆ
        $user->division_id = $validatedData['division_id'];
        $user->position_id = $validatedData['position_id'];
        $user->role_id = $validatedData['role_id'];
        
        $user->save();

        return redirect()->route('admin.users')->with('success', 'อัปเดตข้อมูลผู้ใช้เรียบร้อยแล้ว.');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete(); // ลบข้อมูลผู้ใช้
        return redirect()->route('admin.users')->with('success', 'ลบข้อมูลบุคลากรสำเร็จ.');
    }
    
    public function searchUsers(Request $request)
    {
        $q = $request->input('q');
        
        if ($q != '') {
            // การเชื่อมตาราง users กับ position และการค้นหาชื่อ, นามสกุล หรือชื่อตำแหน่ง
            $users = User::join('position', 'users.position_id', '=', 'position.position_id')
                            // ->join('department', 'users.department_id', '=', 'department.department_id')
                            ->join('role', 'users.role_id', '=', 'role.role_id')
                        ->where('users.name', 'LIKE', '%'.$q.'%')
                        ->orWhere('users.lname', 'LIKE', '%'.$q.'%')
                        ->orWhere('users.email', 'LIKE', '%'.$q.'%')
                        ->orWhere('users.phonenumber', 'LIKE', '%'.$q.'%')
                        ->orWhere('position.position_name', 'LIKE', '%'.$q.'%')
                        // ->orWhere('department.department_name', 'LIKE', '%'.$q.'%')
                        ->orWhere('role.role_name', 'LIKE', '%'.$q.'%')
                        
                        ->select('users.*', 'position.position_name', 'role.role_name')
                        ->paginate(10);

            $users->appends(['q' => $q]);
            $departments = Department::all();
            $divisions = Division::all();
            $positions = Position::all();
            $roles = Role::all();

            return view('admin.users.index', compact('users', 'divisions', 'departments', 'positions', 'roles'));
        }

        return redirect()->route('admin.users');
    }
    


        /**
     *
     *
     * 
     */
    //------------------------- แสดงรายการคำขอ -------------------------
    public function showform()
    {
        $user = auth()->user(); // ดึงข้อมูลผู้ใช้ปัจจุบัน
        $documents = ReqDocument::all(); // ดึงข้อมูลทั้งหมดจาก ReqDocument
    
        // ส่งข้อมูลไปยัง view admin.user.form
        return view('admin.users.form', compact('documents'));
    }
    
}
