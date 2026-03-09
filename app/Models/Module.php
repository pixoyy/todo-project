<?php

namespace App\Models;

use App\Models\Authorization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'modules';
    protected $guarded = [];

    public function authorizations()
    {
        return $this->hasMany(Authorization::class, 'module_id', 'id');
    }
}
