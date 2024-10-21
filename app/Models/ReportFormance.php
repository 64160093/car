<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportFormance extends Model
{
    use HasFactory;

    // ระบุชื่อของตาราง (ถ้าชื่อไม่ตรงกับการตั้งค่าของ Laravel)
    protected $table = 'report_formance';

    // ระบุฟิลด์ที่สามารถกรอกข้อมูลได้
    protected $fillable = [
        'stime',
        'etime',
        'skilo_num',
        'ekilo_num',
        'total_companion',
        'gasoline_cost',
        'expressway_toll',
        'parking_fee',
        'another_cost',
        'total_cost',
        'performance_isgood',
        'comment_issue',
        'req_document_id',
    ];

    // หากฟิลด์ที่เป็น primary key ไม่ใช่ 'id'
    protected $primaryKey = 'report_id';

    // ถ้าตารางนี้ไม่มี timestamp (created_at, updated_at) ให้ระบุดังนี้
    public $timestamps = false;

    // public function reqDocument()
    // {
    //     return $this->belongsTo(ReqDocument::class);
    // }

    public function reqDocument()
    {
        return $this->belongsTo(ReqDocument::class, 'req_document_id', 'document_id');
    }
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'car_id');
    }
    public function province()
    {
        return $this->belongsTo(Province::class, 'provinces_id', 'provinces_id');
    }
    public function carmanUser()
    {
        return $this->belongsTo(User::class, 'carman');
    }

}