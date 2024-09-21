<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReqDocumentUser extends Model
{
    protected $table = 'req_document_user';

    // Define relationship to User model if needed
    public function users()
    {
        return $this->belongsToMany(User::class, 'req_document_user', 'req_document_id', 'user_id');
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
        return $this->belongsToMany(User::class, 'req_document_user', 'req_document_id', 'user_id')
            ->withTimestamps();
    }
}
