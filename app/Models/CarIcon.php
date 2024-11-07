<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarIcon extends Model
{
    use HasFactory;
    protected $table = 'car_icon'; // ระบุชื่อของตารางที่ถูกต้อง
    protected $fillable = ['name'];
}
