<?php

namespace App\Http\Controllers;

use App\Models\ReqDocument;
use App\Models\ReqDocumentUser;
use App\Models\User;
use Illuminate\Http\Request;

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

            // ดึงเอกสารที่ผู้ใช้เกี่ยวข้องและเรียงตามวันที่ล่าสุด
            $documents = ReqDocument::whereHas('reqDocumentUsers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
                ->orderBy('created_at', 'desc') // เรียงลำดับตามวันที่สร้างล่าสุด
                ->get();

            return view('document-history', compact('documents'));
        } else {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
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
            });

            // ดึงเอกสารที่มี allow_division = 'approved'
            $approvedDivision = ReqDocument::where('allow_division', 'approved');
            $approvedOpcar = ReqDocument::where('allow_opcar', 'approved');
            $approvedOfficer = ReqDocument::where('allow_officer', 'approved');
            $approvedDirector = ReqDocument::where('allow_director', 'approved');

            if ($isReviewer) {
                // ดึงเอกสารที่ต้องตรวจสอบและผ่านเงื่อนไข
                $reviewDocuments = ReqDocument::whereHas('reqDocumentUsers', function ($query) use ($user) {
                    $query->where('req_document_user.user_id', '!=', $user->id)
                        ->where(function ($query) use ($user) {
                            $this->applyDivisionRoleFilter($query, $user->role_id);
                        });
                });

                $documents = $userDocuments->get()
                    ->merge($reviewDocuments->get())
                    ->sortBy('document_id');

            } elseif ($user->role_id == 12) {
                $documents = $approvedDivision
                    ->orderBy('document_id', 'asc')
                    ->get();

            } elseif ($user->role_id == 2) {
                $documents = $approvedOpcar
                    ->orderBy('document_id', 'asc')
                    ->get();

            } elseif ($user->role_id == 3) {
                $documents = $approvedOfficer
                    ->orderBy('document_id', 'asc')
                    ->get();

            } elseif ($user->role_id == 11) {
                $documents = $approvedDirector
                    ->orderBy('document_id', 'asc')
                    ->get();
                return view('driver.schedule', compact('documents'));
            }

            return view('permission-form', compact('documents'));

        } else {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
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

    // public function permission()
    // {
    //     if (auth()->check()) {

    //         $user = auth()->user();
    //         $isReviewer = in_array($user->role_id, [4, 6, 7, 8, 9, 10, 13, 14, 15, 16]);

    //         $userDocuments = ReqDocument::whereHas('reqDocumentUsers', function ($query) use ($user) {
    //             $query->where('req_document_user.user_id', $user->id); // เอกสารที่ผู้ใช้ส่งเอง
    //         });

    //         if ($isReviewer) {
    //             // ดึงเอกสารที่ต้องตรวจสอบ
    //             $reviewDocuments = ReqDocument::whereHas('reqDocumentUsers', function ($query) use ($user) {
    //                 $query->where('req_document_user.user_id', '!=', $user->id) // ไม่ดึงเอกสารที่ผู้ใช้ส่งเอง
    //                     ->where(function ($query) use ($user) {
    //                         // ฟังก์ชันกรองเอกสารตาม division_id และ role_id
    //                         $this->applyDivisionRoleFilter($query, $user->role_id);
    //                     });
    //             });

    //             // รวมเอกสารที่ผู้ใช้ส่งเองกับเอกสารที่ต้องตรวจสอบ
    //             $documents = $userDocuments->union($reviewDocuments)->orderBy('created_at', 'asc')->get();
    //         } else {
    //             $documents = $userDocuments->orderBy('created_at', 'asc')->get();
    //         }
    //         return view('permission-form', compact('documents'));

    //     } else {
    //         return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
    //     }
    // }


    // ฟังก์ชันช่วยเพื่อแยกเงื่อนไขการกรองตาม role และ division
    // private function applyDivisionRoleFilter($query, $roleId)
    // {
    //     if ($roleId == 4) {
    //         $query->where('req_document_user.division_id', 1);
    //     } elseif ($roleId == 6) {
    //         $query->where('req_document_user.division_id', 3);
    //     } elseif ($roleId == 7) {
    //         $query->where('req_document_user.division_id', 4);
    //     } elseif ($roleId == 8) {
    //         $query->where('req_document_user.division_id', 5);
    //     } elseif ($roleId == 9) {
    //         $query->where('req_document_user.division_id', 6);
    //     } elseif ($roleId == 10) {
    //         $query->where('req_document_user.division_id', 7);

    //     } elseif ($roleId == 13) {
    //         $query->where('req_document_user.division_id', 2)
    //             ->where('req_document_user.department_id', 1);
    //     } elseif ($roleId == 14) {
    //         $query->where('req_document_user.division_id', 2)
    //             ->where('req_document_user.department_id', 2);
    //     } elseif ($roleId == 15) {
    //         $query->where('req_document_user.division_id', 2)
    //             ->where('req_document_user.department_id', 3);
    //     } elseif ($roleId == 16) {
    //         $query->where('req_document_user.division_id', 2)
    //             ->where('req_document_user.department_id', 4);
    //     }
    // }


    public function show(Request $request)
    {
        $id = $request->input('id'); // รับค่า id ของเอกสารที่ส่งเข้ามา

        $documents = ReqDocument::whereHas('reqDocumentUsers', function ($query) use ($id) {
            $query->where('document_id', $id);
        })->get();

        return view('permission-form-allow', compact('documents'));
    }

    /**
     * "updateStatus"
     *
     * 
     */
    public function updateStatus(Request $request)
    {
        $documentId = $request->input('document_id');
        $statusdivision = $request->input('statusdivision');
        $statusdepartment = $request->input('statusdepartment');
        $statusopcar = $request->input('statusopcar');
        $statusofficer = $request->input('statusofficer');
        $statusdirector = $request->input('statusdirector');

        $document = ReqDocument::where('document_id', $documentId)->first();

        if ($document) {
            if ($statusdivision) {
                $document->allow_division = $statusdivision;
            }
            if ($statusdepartment) {
                $document->allow_department = $statusdepartment;
            }
            if ($statusopcar) {
                $document->allow_opcar = $statusopcar;
            }
            if ($statusofficer) {
                $document->allow_officer = $statusofficer;
            }
            if ($statusdirector) {
                $document->allow_director = $statusdirector;
            }
            $document->save();

            return redirect()->route('documents.index')
                ->with('success', 'สถานะถูกอัปเดตเรียบร้อยแล้ว');
        } else {
            return redirect()->route('documents.show')
                ->with('error', 'ไม่พบเอกสาร');
        }
    }



}