<?php

namespace Database\Seeders;

use App\Models\Authorization;
use App\Models\AuthorizationType;
use App\Models\Module;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthorizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET foreign_key_checks = 0;');
        Authorization::truncate();
        DB::statement('SET foreign_key_checks = 1;');

        $role = Role::where('name', 'Master')->first();
        $modules = Module::all();
        $authorizationTypes = AuthorizationType::all();
        foreach ($modules as $module) {
            foreach ($authorizationTypes as $authorizationType) {
                Authorization::create([
                    'role_id' => $role->id,
                    'authorization_type_id' => $authorizationType->id,
                    'module_id' => $module->id,
                ]);
            }
        }
    }
}
