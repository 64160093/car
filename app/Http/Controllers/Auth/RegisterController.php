<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Department;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    protected $redirectTo = '/login';

    public function __construct()
    {
        
        $this->middleware(function ($request, $next) {
            if (auth()->user() && auth()->user()->is_admin == 1) {
                return $next($request);
            }
            return redirect('/');
        });
    }

    public function showRegistrationForm()
    {
        $divisions = Division::all();
        $departments = Department::all();
        $positions = Position::all();
        $roles = Role::all();
        return view('auth.register', compact('divisions', 'departments', 'positions', 'roles'));
    }

    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phonenumber' => ['required', 'digits:10', 'regex:/^[0-9]+$/'],
            'division' => ['required', 'exists:division,division_id'],
            'position_id' => ['required', 'exists:position,position_id'],
            'role_id' => ['required', 'exists:role,role_id'],
        ]);

        $validator->sometimes('department_id', [
            'required',
            'exists:department,department_id'
        ], function ($input) {
            return $input->division == 2;
        });

        return $validator;
    }

    protected function create(array $data)
    {
        \Log::info('Register Data:', $data);

        return User::create([
            'name' => $data['name'],
            'lname' => $data['lname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => '0',
            'phonenumber' => $data['phonenumber'],
            'division_id' => $data['division'],
            'department_id' => $data['department_id'] ?? null,
            'position_id' => $data['position_id'],
            'role_id' => $data['role_id'],
        ]);
    }

    // เพิ่มฟังก์ชัน register เพื่อรับข้อมูลจากการสมัคร
    public function register(Request $request)
    {
        // ตรวจสอบข้อมูลจากแบบฟอร์ม
        $this->validator($request->all())->validate();
        // สร้างผู้ใช้ใหม่
        $user = $this->create($request->all());
        // เรียก event Registered เพื่อให้ Laravel รู้ว่ามีการสมัครผู้ใช้ใหม่
        event(new Registered($user));

        return redirect('/admin/users')->with('success', 'เพิ่มข้อมูลบุคลากรสำเร็จ');
    }
}
