<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
    public function login(Request $request){
        $input = $request->all();

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(auth()->attempt(['email' => $input['email'], 'password' => $input['password']])){
            if (auth()->user()->is_admin == 1) {
                return redirect()->route('admin.home');
            } elseif (auth()->user()->role_id == 11) {
                return redirect()->route('documents.index'); // เปลี่ยนจาก 'driver.schedule' เป็น 'documents.index'
            } else {
                return redirect("/")->with('status', 'เข้าสู่ระบบเรียบร้อย');
            }
        } else {
            return redirect()->route('login')->with('error','Email-address and Password are wrong.');
        }
              
    }
}
