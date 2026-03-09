<?php

namespace Database\Seeders;

use App\Models\ModuleGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET foreign_key_checks = 0;');
        ModuleGroup::truncate();

        DB::statement('SET foreign_key_checks = 1;');
        ModuleGroup::insert([
            [
                'name' => 'Setting',
                'order' => 5,
                'icon' => 'setting',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
