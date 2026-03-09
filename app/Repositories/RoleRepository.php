<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Models\Role;

class RoleRepository
{
    public function getRoles($onlyActive = false)
    {
        if ($onlyActive) {
            $roles = Role::active()->get();
        } else {
            $roles = Role::all();
        }
        return $roles;
    }

    public function getData($n = 10)
    {
        $roles = Role::orderByRaw("(CASE WHEN name = 'Master' THEN 1 ELSE 0 END) DESC, status DESC, id DESC")->paginate($n);
        return $roles;
    }

    public function getById($id)
    {
        $role = Role::notMaster()->find($id);
        return $role;
    }

    public function create($request)
    {
        Role::create([
            'name' => $request->name,
            'status' => isset($request->status) ? $request->status : 0,
        ]);
    }

    public function update($id, $request)
    {
        $role = Role::notMaster()->find($id);
        if (!isset($role)) return;

        $status = isset($request->status) ? $request->status : 0;
        $role->update([
            'name' => $request->name,
            'status' => $status,
        ]);

        if ($status == 0) {
            Admin::where('role_id', $role->id)->update([
                'status' => $status,
            ]);
        }
    }

    public function delete($id)
    {
        $role = Role::notMaster()->find($id);
        Admin::where('role_id', $role->id)->update([
            'role_id' => null,
            'status' => 0,
        ]);
        $role->delete();
    }
}