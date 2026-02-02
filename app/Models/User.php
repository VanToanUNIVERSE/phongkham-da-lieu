<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username',
        'password',
        'gender',
        'birthYear',
        'phone',
        'status',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'gender' => 'boolean',
    ];

    // Quan hệ: User thuộc về 1 Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function doctor() {
        return $this->hasOne(Doctor::class);
    }
    public function prescriptions() {
        return $this->hasMany(Prescription::class);
    }
}
