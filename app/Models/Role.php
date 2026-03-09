<?php

namespace App\Models;

use App\Models\Authorization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'roles';
    protected $guarded = [];

    public function authorizations()
    {
        return $this->hasMany(Authorization::class, 'role_id', 'id');
    }

    public function scopeNotMaster($query)
    {
        $query->where('name', '<>', 'Master');
    }

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }
}
