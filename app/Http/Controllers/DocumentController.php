<?php

namespace App\Http\Controllers;

use App\Models\ReqDocument;
use App\Models\ReqDocumentUser;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Vehicle;

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
            })->get();

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
        $id = $request->input('id'); // รับค่า id ของเอกสารที่ส่งเข้ามา
    
        $documents = ReqDocument::whereHas('reqDocumentUsers', function ($query) use ($id) {
            $query->where('document_id', $id);
        })->get();
    
        return view('permission-form-allow', compact('documents', 'vehicles','users'));
    }
    
    /**
     * "updateStatus" "SelectDriver" "SelectCar"
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

        // ค้นหาเอกสารตาม document_id
        $document = ReqDocument::where('document_id', $documentId)->first();

        if ($document) {
            // อัปเดตสถานะต่าง ๆ
            if ($statusdivision) {
                $document->allow_division = $statusdivision;
                if ($statusdivision == 'rejected' && $request->input('notallowed_reason_division')) {
                    $document->notallowed_reason = $request->input('notallowed_reason_division');
                } else {
                    $document->notallowed_reason = null;
                }
            }

            if ($statusdepartment) {
                $document->allow_department = $statusdepartment;
                if ($statusdepartment == 'rejected' && $request->input('notallowed_reason_department')) {
                    $document->notallowed_reason = $request->input('notallowed_reason_department');
                } else {
                    $document->notallowed_reason = null;
                }
            }

            if ($statusopcar) {
                $document->allow_opcar = $statusopcar;
                if ($statusopcar == 'rejected' && $notallowedReason) {
                    $document->notallowed_reason = $notallowedReason;
                } else {
                    $document->notallowed_reason = null; 
                }
            }
            
            if ($statusofficer) {
                $document->allow_officer = $statusofficer;
                if ($statusofficer == 'rejected' && $request->input('notallowed_reason_officer')) {
                    $document->notallowed_reason = $request->input('notallowed_reason_officer');
                } else {
                    $document->notallowed_reason = null;
                }
            }

            if ($statusdirector) {
                $document->allow_director = $statusdirector;
                if ($statusdirector == 'rejected' && $request->input('notallowed_reason_director')) {
                    $document->notallowed_reason = $request->input('notallowed_reason_director');
                } else {
                    $document->notallowed_reason = null;
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



}