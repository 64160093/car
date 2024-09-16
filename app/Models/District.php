<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $table = 'district';
    // public function amphoe()
    // {
    //     return $this->belongsTo(Amphoe::class);
    // }
}