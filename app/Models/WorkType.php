<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkType extends Model
{
    use HasFactory;

    protected $table = 'work_type'; // ชื่อตารางในฐานข้อมูล
    protected $primaryKey = 'work_id'; // กำหนดคีย์หลัก
    public $timestamps = false; // ถ้าไม่ใช้ฟิลด์ timestamps ในตาราง
}
