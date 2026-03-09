<?php

namespace App\Repositories;

use App\Models\Authorization;
use App\Models\Module;
use App\Models\Role;

class AuthorizationRepository
{
    public function getModules($roleId)
    {
        $modules = Module::where('is_shown', 1)->with(['authorizations' => fn ($q) => $q->where('role_id', $roleId)])->get();
        return $modules;
    }

    public function save($request)
    {
        $exists = Role::where('name', '<>', 'Master')->where('id', $request->role_id)->exists();
        if (!$exists) return;
        $role_id = $request->role_id;

        Authorization::where('role_id', $role_id)->delete();
        if (isset($request->authorizations)) {
            $authorizations = [];

            foreach ($request->authorizations as $module_id => $types) {
                $exists = Module::where('id', $module_id)->exists();
                if (!$exists) continue;

                foreach ($types as $authorization_type_id) {
                    if ($authorization_type_id >= 1 && $authorization_type_id <= 4) {
                        $authorizations[] = [
                            'role_id' => $role_id,
                            'authorization_type_id' => $authorization_type_id,
                            'module_id' => $module_id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
            Authorization::insert($authorizations);
        }
    }
}
