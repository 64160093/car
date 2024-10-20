<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\FullCalendarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUsers;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReqDocumentController;
use App\Http\Controllers\DocumentController;
// use App\Http\Controllers\DriverScheduleController;
use App\Http\Controllers\ReportDocumentController;
use App\Http\Controllers\PDFController;



// เส้นทางหลักของแอปพลิเคชัน
Route::get('/', function () {
    return view('welcome');
})->name(name: 'welcome');

// เส้นทางสำหรับการยืนยันตัวตน
Auth::routes();


Route::get('/home', [HomeController::class, 'index'])
    ->name('home')
    ->middleware(IsUsers::class);


// เส้นทางสำหรับหน้าแรกของผู้ดูแลระบบ
Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('admin.home')
    ->middleware(IsAdmin::class);

// แก้ไขโปรไฟล์
Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit')->middleware('auth');
Route::put('/profile', [UserController::class, 'update'])->name('profile.update');



//แสดงข้อมูลรถ เปลี่ยนสถานะ ลบค่า
Route::get('/vehicles', [AdminController::class, 'showVehicles'])->name('show.vehicles')
    ->middleware(IsAdmin::class);
Route::post('/vehicles', [AdminController::class, 'storeVehicle'])->name('store.vehicle');
Route::post('/vehicles/update-status/{id}', [AdminController::class, 'updateStatus'])->name('vehicles.updateStatus');
Route::delete('/vehicles/{id}', [AdminController::class, 'destroy'])->name('vehicles.destroy');


Route::get('/reqdocument', [ReqDocumentController::class, 'create'])->name('reqdocument.create');
Route::post('/reqdocument', [ReqDocumentController::class, 'store'])->name('reqdocument.store');
Route::get('/get-amphoes/{provinceId}', [ReqDocumentController::class, 'getAmphoes']);
Route::get('/get-districts/{amphoeId}', [ReqDocumentController::class, 'getDistricts']);



use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

Route::get('/signatures/{filename}', function ($filename) {
    $path = 'signatures/' . $filename;

    // ตรวจสอบว่ามีไฟล์อยู่ในระบบหรือไม่
    if (!Storage::exists($path)) {
        abort(404);
    }

    $userId = Auth::id();    // ดึง ID ของผู้ใช้ที่เข้าสู่ระบบ

    // ตรวจสอบว่า ID ของผู้ใช้ตรงกับ ID ที่อยู่ในชื่อไฟล์หรือไม่
    if (strpos($filename, $userId . '_signature') !== 0) {
        abort(403); // ห้ามเข้าถึงหาก ID ไม่ตรงกัน
    }

    // ส่งไฟล์กลับไปยังผู้ใช้
    return Response::file(storage_path('app/' . $path));
})->middleware('auth');



//แอดมินแก้ไขข้อมูลผู้ใช้ ค้นหา ดูรายละเอียดคำขออนญาต
Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users')
    ->middleware(IsAdmin::class);
Route::get('/admin/users/edit/{id}', [AdminController::class, 'editUser'])->name('admin.users.edit')
    ->middleware(IsAdmin::class);
Route::post('/admin/users/update/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
Route::post('/admin/users/delete/{id}', [AdminController::class, 'destroyUser'])->name('admin.users.delete');
Route::any('/admin/users/search', [AdminController::class, 'searchUsers'])->name('admin.users.search')
    ->middleware(IsAdmin::class);
Route::get('/admin/users/form', [AdminController::class, 'showform'])->name('admin.users.form')
    ->middleware(IsAdmin::class);
    
    Route::get('/admin/users/searchform', [AdminController::class, 'searchForm'])->name('admin.users.searchform');


//แสดงประวัติการขอ และ แสดงรายละเอียดคำขอ
Route::get('/document-history', [DocumentController::class, 'index'])->name('documents.history');
Route::get('/reviewform', [DocumentController::class, 'reviewForm'])->name('documents.review');
Route::get('/reviewstatus', [DocumentController::class, 'reviewStatus'])->name('documents.status');


Route::get('/permission-form', [DocumentController::class, 'permission'])->name('documents.index');
Route::get('/permission-form-allow', [DocumentController::class, 'show'])->name('documents.show')->middleware('auth');
Route::post('/update-status', [DocumentController::class, 'updateStatus'])->name('documents.updateStatus');


Route::get('/report[id]', [ReportDocumentController::class, 'index'])->name('report.index');
Route::post('/report', [ReportDocumentController::class, 'store'])->name('report.submit');
Route::get('/reportdoc/show/{id}', [ReportDocumentController::class, 'show'])->name('reportdoc.show');

Route::get('/generate-pdf', [PDFController::class, 'generatePDF'])->name('PDF.document');

Route::get('/report/pdf/{id}', [PDFController::class, 'generateReportPDF'])->name('report.showRepDoc.pdf');
