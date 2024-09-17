<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReqDocumentUser extends Model
{
    protected $table = 'req_document_user';

    // Define relationship to User model if needed
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
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
}
