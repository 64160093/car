<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReqDocument extends Model
{
    protected $table = 'req_document';

    protected $primaryKey = 'document_id';
    public $incrementing = true; 
    protected $keyType = 'int'; 
    public $timestamps = true;  // ใช้ timestamps ที่มีในตาราง


    protected $fillable = [
        'companion_name',
        'objective',
        'related_project',
        'location',
        'car_pickup',
        'reservation_date',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'sum_companion',
        'car_type',
        'provinces_id',
        'amphoe_id',
        'district_id',
        'work_id',

    ];


    public function province()
    {
        return $this->belongsTo(Province::class, 'provinces_id', 'provinces_id');
    }

    public function amphoe()
    {
        return $this->belongsTo(Amphoe::class, 'amphoe_id', 'amphoe_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'district_id');
    }

    public function workType()
    {
        return $this->belongsTo(WorkType::class, 'work_id');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'req_document_user', 'req_document_id', 'user_id');
    }

    public function reqDocumentUsers()
    {
        return $this->hasMany(ReqDocumentUser::class, 'req_document_id', 'document_id');
    }

    public function isEmpty()
    {
        return empty($this->content);
    }

}