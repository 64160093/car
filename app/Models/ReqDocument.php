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
        'car_id',
        'carman',
        'car_controller',
        'cancel_allowed',
        'cancel_reason',

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
        return $this->hasMany(ReqDocumentUser::class, 'req_document_id', 'document_id', 'user_id');
    }

    public function isEmpty()
    {
        return empty($this->content);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'car_id');
    }
    
    public function carmanUser()
    {
        return $this->belongsTo(User::class, 'carman');
    }

    public function reportFormance()
    {
        return $this->hasOne(ReportFormance::class, 'req_document_id', 'document_id');
    }

    public function carController()
    {
        return $this->belongsTo(User::class, 'car_controller');
    }
    public function companions()
    {
        return $this->belongsToMany(User::class, 'req_document_user', 'req_document_id', 'user_id'); // กำหนดชื่อของตารางเชื่อม
    }

    public function DivisionAllowBy()
    {
        return $this->belongsTo(User::class, 'approved_by_division');
    }

    public function DepartmentAllowBy()
    {
        return $this->belongsTo(User::class, 'approved_by_department');
    }

    public function OpcarAllowBy()
    {
        return $this->belongsTo(User::class, 'approved_by_opcar');
    }

    public function OfficerAllowBy()
    {
        return $this->belongsTo(User::class, 'approved_by_officer');
    }

    public function DirectorAllowBy()
    {
        return $this->belongsTo(User::class, 'approved_by_director');
    }
    
    public function CarmenAllowBy()
    {
        return $this->belongsTo(User::class, 'approved_by_carman');
    }
}