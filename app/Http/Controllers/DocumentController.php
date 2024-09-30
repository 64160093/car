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

            $documents = ReqDocument::whereHas('reqDocumentUsers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();

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

            $userDocuments = ReqDocument::with(['reqDocumentUsers.user.division','reqDocumentUsers.user.department', 'reqDocumentUsers.user.position']) // เพิ่มความสัมพันธ์ที่นี่
                ->whereHas('reqDocumentUsers', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                });

            if ($isReviewer) {
                $reviewDocuments = ReqDocument::with(['reqDocumentUsers.user.division','reqDocumentUsers.user.department', 'reqDocumentUsers.user.position']) // เพิ่มความสัมพันธ์ที่นี่
                    ->whereHas('reqDocumentUsers', function ($query) use ($user) {
                        $query->where('user_id', '!=', $user->id)
                            ->where(function ($query) use ($user) {
                                $this->applyDivisionRoleFilter($query, $user->role_id);
                            });
                    });

                $documents = $userDocuments->union($reviewDocuments)->orderBy('created_at', 'asc')->get();
            } else {
                $documents = $userDocuments->orderBy('created_at', 'asc')->get();
            }
            return view('permission-form', compact('documents'));
        } else {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }
    }



    // ฟังก์ชันช่วยเพื่อแยกเงื่อนไขการกรองตาม role และ division
    private function applyDivisionRoleFilter($query, $roleId)
    {
        if ($roleId == 4) {
            $query->where('req_document_user.division_id', 1);
        } elseif ($roleId == 6) {
            $query->where('req_document_user.division_id', 3);
        } elseif ($roleId == 7) {
            $query->where('req_document_user.division_id', 4);
        } elseif ($roleId == 8) {
            $query->where('req_document_user.division_id', 5);
        } elseif ($roleId == 9) {
            $query->where('req_document_user.division_id', 6);
        } elseif ($roleId == 10) {
            $query->where('req_document_user.division_id', 7);

        } elseif ($roleId == 13) {
            $query->where('req_document_user.division_id', 2)
                ->where('req_document_user.department_id', 1);
        } elseif ($roleId == 14) {
            $query->where('req_document_user.division_id', 2)
                ->where('req_document_user.department_id', 2);
        } elseif ($roleId == 15) {
            $query->where('req_document_user.division_id', 2)
                ->where('req_document_user.department_id', 3);
        } elseif ($roleId == 16) {
            $query->where('req_document_user.division_id', 2)
                ->where('req_document_user.department_id', 4);
        }
    }


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

        $document = ReqDocument::where('document_id', $documentId)->first();

        if ($document) {
            if ($statusdivision) {
                $document->allow_division = $statusdivision;
            }

            if ($statusdepartment) {
                $document->allow_department = $statusdepartment;
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