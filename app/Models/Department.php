<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'department'; // กรณีที่ชื่อตารางเป็น departments (plural)
    protected $fillable = ['department_name'];

}
