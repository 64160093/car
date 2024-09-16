<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarIcon extends Model
{
    use HasFactory;
    protected $table = 'car_icon'; // ระบุชื่อของตารางที่ถูกต้อง
    protected $fillable = [   
        'icon_id',   
        'icon_img',
        'icon_color',
        'type_name_id',
        'type_name',
    ];
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'icon_id', 'icon_id');
    }
}
