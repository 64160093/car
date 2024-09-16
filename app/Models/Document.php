<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    // กำหนดชื่อเทเบิล
    protected $table = 'req_document';

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
        return $this->belongsTo(User::class, 'id');
    }

    // ความสัมพันธ์กับโมเดล Province
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    // ความสัมพันธ์กับโมเดล Amphoe
    public function amphoe()
    {
        return $this->belongsTo(Amphoe::class, 'amphoe_id');
    }

    // ความสัมพันธ์กับโมเดล District
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    // ความสัมพันธ์กับโมเดล CarIcon
    public function carIcon()
    {
        return $this->belongsTo(CarIcon::class, 'icon_id');
    }
}
