<?php

namespace App\Models;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Authenticatable implements CanResetPassword,MustVerifyEmail
{
    use HasFactory, SoftDeletes,Notifiable;
    protected $table = 'admins';
    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function scopeNotMaster($query)
    {
        $query->whereHas('role', fn ($q) => $q->where('name', '<>', 'Master'));
    }

    public function isMaster()
    {
        return $this->role->name === 'Master'; // Adjust based on your role structure
    }


}
