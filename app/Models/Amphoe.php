<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amphoe extends Model
{
    use HasFactory;
    protected $table = 'amphoe';

    // public function province()
    // {
    //     return $this->belongsTo(Province::class);
    // }

    // public function districts()
    // {
    //     return $this->hasMany(District::class);
    // }
}