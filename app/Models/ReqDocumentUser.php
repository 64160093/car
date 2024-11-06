<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReqDocumentUser extends Model
{
    protected $table = 'req_document_user';

    protected $fillable = [
        'req_document_id',
        'user_id',
        'report_id',
    ];

    // Define relationship to User model if needed
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    // ความสัมพันธ์กับ Department
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function reqDocument()
    {
        return $this->belongsTo(ReqDocument::class, 'req_document_id', 'document_id');
    }

    public function report()
    {
        return $this->belongsTo(ReportFormance::class);
    }
    
}