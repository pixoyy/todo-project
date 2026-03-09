<?php

namespace App\Models;

use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuleGroup extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'module_groups';
    protected $guarded = [];

    public function modules()
    {
        return $this->hasMany(Module::class, 'module_group_id', 'id');
    }
}

