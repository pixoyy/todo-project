<?php

namespace Database\Seeders;

use App\Models\AuthorizationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthorizationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET foreign_key_checks = 0;');
        AuthorizationType::truncate();
        DB::statement('SET foreign_key_checks = 1;');

        AuthorizationType::insert([
            [
                'name' => 'read',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'create',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'update',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'delete',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
