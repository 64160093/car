<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    public function amphoes()
    {
        return $this->hasMany(Amphoe::class);
    }
}