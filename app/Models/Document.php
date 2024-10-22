<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    // กำหนดชื่อเทเบิล
    protected $table = 'req_document';
    protected $primaryKey = 'document_id'; // หากใช้ document_id เป็นคีย์หลัก

    // กำหนดคอลัมน์ที่สามารถ fill ได้
    protected $fillable = [
        'document_id',
        'requester_id',
        'objective',
        'location',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'sum_companion',
        'car_type',
        'created_at',
        'updated_at',
    ];

    // ความสัมพันธ์กับโมเดล User
    public function users()
    {
        return $this->belongsToMany(User::class, 'req_document_user', 'req_document_id', 'user_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'provinces_id', 'provinces_id');
    }

    public function amphoe()
    {
        return $this->belongsTo(Amphoe::class, 'amphoe_id', 'amphoe_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'district_id');
    }
    // ความสัมพันธ์กับโมเดล CarIcon
    public function carIcon()
    {
        return $this->belongsTo(CarIcon::class, 'icon_id');
    }
    public function reqDocumentUsers()
    {
        return $this->belongsToMany(User::class, 'req_document_user', 'req_document_id', 'user_id');
    }
    public function reqDocuments()
    {
        return $this->belongsToMany(ReqDocument::class, 'req_document_user', 'user_id', 'req_document_id');
    }
    public $timestamps = true; // เปิดใช้ timestamp (ค่าเริ่มต้นคือ true)

}
