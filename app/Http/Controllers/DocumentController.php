<?php

namespace App\Http\Controllers;

use App\Models\ReqDocument;
use App\Models\ReqDocumentUser;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Province;
use App\Models\Amphoe;
use App\Models\District;
use App\Models\Document;

class DocumentController extends Controller
{
    /**
     *
     *
     * 
     */
    //------------------------- แสดงประวัติการขอของตนเอง -------------------------
    public function index()
    {
        if (auth()->check()) {
            $user = auth()->user();

            $documents = ReqDocument::whereHas('reqDocumentUsers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->orderBy('created_at', 'desc')->get();

            return view('document-history', compact('documents'));
        } else {
            return redirect()->route('login');
        }
    }

    public function reviewForm(Request $request)
    {
        $id = $request->input('id');
        if (!$id) {
            return redirect()->route('documents.history')->with('error', 'ไม่พบข้อมูลเอกสาร');
        }

        $document = ReqDocument::with(['reqDocumentUsers', 'workType', 'province', 'amphoe', 'district'])->findOrFail($id);

        return view('reviewform', compact('document'));
    }

    public function reviewStatus(Request $request)
    {
        $id = $request->input('id');
        if (!$id) {
            return redirect()->route('documents.status')->with('error', 'ไม่พบข้อมูลเอกสาร');
        }

        $document = ReqDocument::with(['reqDocumentUsers'])->findOrFail($id);

        return view('reviewstatus', compact('document'));
    }



    /**
     *
     *
     * 
     */
    //------------------------- แสดงรายการคำขอที่รออนุมัติ -------------------------
    public function permission()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $isReviewer = in_array($user->role_id, [4, 6, 7, 8, 9, 10, 13, 14, 15, 16]);

            // ดึงเอกสารที่ผู้ใช้ส่งเอง
            $userDocuments = ReqDocument::whereHas('reqDocumentUsers', function ($query) use ($user) {
                $query->where('req_document_user.user_id', $user->id)
                    ->where(function ($query) use ($user) {
                        $this->applyDivisionRoleFilter($query, $user->role_id);
                    });
            })->orderBy('document_id', 'desc'); // เรียงลำดับจากมากไปน้อย

            // ดึงเอกสารที่มี allow_division = 'approved'
            $approvedDivision = ReqDocument::where('allow_division', 'approved');
            $approvedOpcar = ReqDocument::where('allow_opcar', 'approved');
            $approvedOfficer = ReqDocument::where('allow_officer', 'approved');
            $approvedDirector = ReqDocument::where('allow_director', 'approved');

            // ตรวจสอบเอกสารตาม role_id และเงื่อนไข division_id = 2
            if ($isReviewer) {
                // เอกสารที่ต้องตรวจสอบ (นอกเหนือจากที่ผู้ใช้ส่งเอง)
                $reviewDocuments = ReqDocument::whereHas('reqDocumentUsers', function ($query) use ($user) {
                    $query->where('req_document_user.user_id', '!=', $user->id)
                        ->where(function ($query) use ($user) {
                            $this->applyDivisionRoleFilter($query, $user->role_id);
                        });
                });

                $documents = $userDocuments->get()
                    ->merge($reviewDocuments->get())
                    ->sortByDesc('document_id'); // เรียงจากมากไปน้อย

            } elseif ($user->role_id == 12) {        //คนสั่งรถ
                // เอกสารที่ต้องส่งไปยัง role_id = 12
                $documents = $approvedDivision
                    ->orderBy('document_id', 'desc')
                    ->get();
                return view('opcar.op_permission-form', compact('documents'));


            } elseif ($user->role_id == 2) {        //หัวหน้าสำนักงาน
                $documents = $approvedOpcar
                    ->orderBy('document_id', 'desc')
                    ->get();

            } elseif ($user->role_id == 3) {        //ผู้อำนวยการ
                $documents = $approvedOfficer
                    ->orderBy('document_id', 'desc')
                    ->get();

            } elseif ($user->role_id == 11) { // คนขับรถ
                $documents = $approvedDirector
                    ->where('carman', $user->id) // กรองเอกสารที่มี carman เท่ากับ user id
                    ->orderBy('document_id', 'desc')
                    ->get();
                return view('driver.schedule', compact('documents'));

            } elseif ($user->role_id == 5) {        //หัวหน้าฝ่ายวิจัยวิทยาศาสตร์ทางทะเล
                $documents = ReqDocument::where('allow_department', 'approved')
                    ->whereHas('reqDocumentUsers', function ($query) {
                        $query->where('req_document_user.division_id', 2);
                    })
                    ->orderBy('document_id', 'desc')
                    ->get();
            }

            return view('permission-form', compact('documents'));

        } else {
            return redirect()->route('login');
        }
    }

    private function applyDivisionRoleFilter($query, $roleId)
    {
        switch ($roleId) {
            case 4:
                $query->where('req_document_user.division_id', 1);
                break;
            case 6:
                $query->where('req_document_user.division_id', 3);
                break;
            case 7:
                $query->where('req_document_user.division_id', 4);
                break;
            case 8:
                $query->where('req_document_user.division_id', 5);
                break;
            case 9:
                $query->where('req_document_user.division_id', 6);
                break;
            case 10:
                $query->where('req_document_user.division_id', 7);
                break;
            case 13:
                $query->where('req_document_user.division_id', 2)
                    ->where('req_document_user.department_id', 1);
                break;
            case 14:
                $query->where('req_document_user.division_id', 2)
                    ->where('req_document_user.department_id', 2);
                break;
            case 15:
                $query->where('req_document_user.division_id', 2)
                    ->where('req_document_user.department_id', 3);
                break;
            case 16:
                $query->where('req_document_user.division_id', 2)
                    ->where('req_document_user.department_id', 4);
                break;
            default:
                break;      //roleId ไม่ตรงกับเงื่อนไข

        }
    }

    public function show(Request $request)
    {
        $vehicles = Vehicle::all();
        $users = User::all();
        $id = $request->input('id'); // รับค่า id ของเอกสารที่ส่งเข้าม

        $documents = ReqDocument::whereHas('reqDocumentUsers', function ($query) use ($id) {
            $query->where('document_id', $id);
        })->get();

        $user = auth()->user(); // รับข้อมูลผู้ใช้ที่ล็อกอินอยู่

        return view('permission-form-allow', compact('documents', 'vehicles', 'users', 'user'));
    }

    /**
     * "updateStatus" "SelectDriver" "SelectCar" "ShowStatus"
     *
     * 
     */
    public function updateStatus(Request $request)
    {
        // รับข้อมูลจากฟอร์ม
        $documentId = $request->input('document_id');
        $statusdivision = $request->input('statusdivision');
        $statusdepartment = $request->input('statusdepartment');
        $statusopcar = $request->input('statusopcar');
        $statusofficer = $request->input('statusofficer');
        $statusdirector = $request->input('statusdirector');
        $notallowedReason = $request->input('notallowed_reason'); // เพิ่มการรับค่าเหตุผล
        $statuscarman = $request->input('statuscarman');
        $carmanReason = $request->input('carman_reason'); // เพิ่มการรับค่าเหตุผล

        // ค้นหาเอกสารตาม document_id
        $document = ReqDocument::where('document_id', $documentId)->first();

        if ($document) {
            // อัปเดตสถานะต่าง ๆ พร้อมบันทึกผู้อนุญาต
            if ($statusdivision) {
                $document->allow_division = $statusdivision;
                $document->approved_by_division = auth()->user()->id; // เก็บค่าผู้อนุญาต
                if ($statusdivision == 'rejected' && $request->input('notallowed_reason_division')) {
                    $document->notallowed_reason = $request->input('notallowed_reason_division');
                } else {
                    $document->notallowed_reason = null;
                }
            }

            if ($statusdepartment) {
                $document->allow_department = $statusdepartment;
                $document->approved_by_department = auth()->user()->id; // เก็บค่าผู้อนุญาต
                if ($statusdepartment == 'rejected' && $request->input('notallowed_reason_department')) {
                    $document->notallowed_reason = $request->input('notallowed_reason_department');
                } else {
                    $document->notallowed_reason = null;
                }
            }

            if ($statusopcar) {
                $document->allow_opcar = $statusopcar;
                $document->approved_by_opcar = auth()->user()->id; // เก็บค่าผู้อนุญาต
                if ($statusopcar == 'rejected' && $notallowedReason) {
                    $document->notallowed_reason = $notallowedReason;
                } else {
                    $document->notallowed_reason = null;
                }
            }

            if ($statusofficer) {
                $document->allow_officer = $statusofficer;
                $document->approved_by_officer = auth()->user()->id; // เก็บค่าผู้อนุญาต
                if ($statusofficer == 'rejected' && $request->input('notallowed_reason_officer')) {
                    $document->notallowed_reason = $request->input('notallowed_reason_officer');
                } else {
                    $document->notallowed_reason = null;
                }
            }

            if ($statusdirector) {
                $document->allow_director = $statusdirector;
                $document->approved_by_director = auth()->user()->id; // เก็บค่าผู้อนุญาต
                if ($statusdirector == 'rejected' && $request->input('notallowed_reason_director')) {
                    $document->notallowed_reason = $request->input('notallowed_reason_director');
                } else {
                    $document->notallowed_reason = null;
                }
            }

            if ($statuscarman) {
                $document->allow_carman = $statuscarman;
                $document->approved_by_carman = auth()->user()->id; // เก็บค่าผู้อนุญาต
                if ($statuscarman == 'rejected' && $carmanReason) {
                    $document->carman_reason = $carmanReason;
                } else {
                    $document->carman_reason = null;
                }
            }

            // เพิ่ม car_id เฉพาะผู้ใช้ที่มี role_id = 12 เท่านั้น
            if (auth()->user()->role_id == 12) {
                $document->car_id = $request->input('car_id');
                $document->carman = $request->input('carman');
            }

            // บันทึกข้อมูล
            $document->save();

            return redirect()->route('documents.index')
                ->with('success', 'สถานะถูกอัปเดตเรียบร้อยแล้ว');
        } else {
            return redirect()->route('documents.show')
                ->with('error', 'ไม่พบเอกสาร');
        }
    }


    /**
     * Edit Document
     *
     * 
     */
    public function edit(Request $request)
    {
        // รับค่า id จาก query string
        $id = $request->query('id');

        if (!$id) {
            return redirect()->route('documents.history')->with('error', 'ไม่พบข้อมูลเอกสาร');
        }

        // ดึงข้อมูลเอกสารก่อน เพื่อที่จะใช้ในการดึง amphoe และ district
        $document = ReqDocument::with(['reqDocumentUsers', 'workType', 'province', 'amphoe', 'district', 'users'])->findOrFail($id);

        // ดึงข้อมูลอำเภอและตำบลที่สัมพันธ์กับจังหวัดและอำเภอที่อยู่ในเอกสาร
        $provinces = Province::all();
        $amphoe = Amphoe::where('provinces_id', $document->provinces_id)->get(); // ตรวจสอบที่นี่
        $district = District::where('amphoe_id', $document->amphoe_id)->get(); // ตรวจสอบที่นี่

        $users = User::all(); // ดึงข้อมูลผู้ใช้ทั้งหมด

        // ดึง companions จากชื่อของผู้ติดตามที่แยกด้วยเครื่องหมายจุลภาค
        $companions = User::whereIn('id', explode(',', $document->companion_name))->get();

        $user = auth()->user(); // รับข้อมูลผู้ใช้ที่ล็อกอินอยู่

        return view('editDocument', compact('document', 'companions', 'provinces', 'amphoe', 'district', 'users', 'user'));
    }


    public function update(Request $request, $id)
    {
        // Validation rule
        $request->validate([
            'objective' => 'required|string|max:255',
            'companion_name' => 'nullable',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date', // แก้ให้ end_date ต้องไม่ก่อน start_date
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required|string|max:255',
            'car_type' => 'nullable|string|max:255',
            'provinces_id' => 'required|integer',
            'amphoe_id' => 'required|integer',
            'district_id' => 'required|integer',
        ]);

        // ค้นหาเอกสาร
        $document = ReqDocument::findOrFail($id);

        // อัพเดตข้อมูลเอกสาร
        $document->objective = $request->input('objective');
        $document->companion_name = $request->input('companion_name');
        $document->start_date = $request->input('start_date');
        $document->end_date = $request->input('end_date');
        $document->start_time = $request->input('start_time');
        $document->end_time = $request->input('end_time');
        $document->location = $request->input('location');
        $document->car_type = $request->input('car_type');

        // อัพเดตข้อมูลจังหวัด อำเภอ และตำบล
        $document->provinces_id = $request->input('provinces_id');
        $document->amphoe_id = $request->input('amphoe_id');
        $document->district_id = $request->input('district_id');

        // บันทึกการอัพเดตข้อมูล
        $document->save();

        return redirect()->route('documents.history')->with('success', 'บันทึกการแก้ไขสำเร็จ');
    }
    public function getAmphoes($province_id)
    {
        // ตรวจสอบว่ามี province_id ที่ต้องการหรือไม่
        $amphoes = Amphoe::where('provinces_id', $province_id)->get();
        return response()->json(['amphoes' => $amphoes]);
    }

    public function getDistricts($amphoe_id)
    {
        // ตรวจสอบว่ามี amphoe_id ที่ต้องการหรือไม่
        $districts = District::where('amphoe_id', $amphoe_id)->get();
        return response()->json(['districts' => $districts]);
    }

}