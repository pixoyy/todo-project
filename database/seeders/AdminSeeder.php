<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET foreign_key_checks = 0;');
        Admin::truncate();
        DB::statement('SET foreign_key_checks = 1;');

        Admin::insert([
            [
                'role_id' => 1,
                'name' => 'Master',
                'email' => 'master@todo.com',
                'password' => bcrypt('password'),
                'status' => 1,
                'phone_number' => '081234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
