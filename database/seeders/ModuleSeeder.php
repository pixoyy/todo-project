<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET foreign_key_checks = 0;');
        Module::truncate();
        DB::statement('SET foreign_key_checks = 1;');
        Module::insert([
            [
                'module_group_id' => null,
                'name' => 'Dashboard',
                'route' => 'dashboard',
                'order' => 1,
                'icon' => 'dashboard',
                'is_shown' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_group_id' => null,
                'name' => 'Project',
                'route' => 'projects',
                'order' => 2,
                'icon' => 'folder',
                'is_shown' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_group_id' => null,
                'name' => 'Category',
                'route' => 'categories',
                'order' => 3,
                'icon' => 'category',
                'is_shown' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_group_id' => null,
                'name' => 'Task',
                'route' => 'tasks',
                'order' => 4,
                'icon' => 'task',
                'is_shown' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_group_id' => 1,
                'name' => 'Role',
                'route' => 'roles',
                'order' => 1,
                'icon' => null,
                'is_shown' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_group_id' => 1,
                'name' => 'User',
                'route' => 'users',
                'order' => 2,
                'icon' => null,
                'is_shown' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_group_id' => 1,
                'name' => 'Otorisasi',
                'route' => 'authorization',
                'order' => 3,
                'icon' => null,
                'is_shown' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
