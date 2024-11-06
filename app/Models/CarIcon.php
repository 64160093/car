<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarIcon extends Model
{
    use HasFactory;

    protected $table = 'car_icon'; // ระบุชื่อของตารางที่ถูกต้อง

    // เพิ่ม icon_color ใน fillable เพื่อให้สามารถกรอกข้อมูลนี้ได้
    protected $fillable = ['name', 'icon_color'];
}
