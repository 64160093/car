<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusAllow extends Model
{
    protected $table = 'status_allow';
    protected $primaryKey = 'status_id';

    protected $fillable = [
        'allow_department',
        'allow_division',
        'allow_opcar',
        'allow_officer',
        'allow_director',
        'not_allowed',
        'cancel_allowed',
        'status_driver',
    ];
    public function statusAllow()
    {
        return $this->hasOne(StatusAllow::class, 'document_id'); // สมมติว่ามี document_id ใน status_allow
    }
}
