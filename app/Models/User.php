<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Fields that can be mass-assigned
    protected $fillable = [
        'name',
        'lname',
        'email',
        'password',
        'phonenumber',
        'signature_name',
        'is_admin',
        'division_id',   //เก็บค่าเข้าตาราง users
        'department_id',
        'position_id',
        'role_id',


    ];

    // Fields that should be hidden
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casting attributes
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationship with posts
    public function posts()
    {
        return $this->hasMany(PostModel::class)->latest();
    }

    // Generate image URL
    public function getImageURL()
    {
        if ($this->image) {
            return url('storage/' . $this->image);
        }
        return "https://api.dicebear.com/6.x/fun-emoji/svg?seed={$this->name}";
    }
    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id', 'division_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }
    public function reqDocuments()
    {
        return $this->hasMany(ReqDocument::class, 'user_id');
    }
}