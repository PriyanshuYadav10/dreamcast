<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'display_name' => 'Administrator',
                'role_slug' => 'administrator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'display_name' => 'Editor',
                'role_slug' => 'editor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'display_name' => 'User',
                'role_slug' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'display_name' => 'Guest',
                'role_slug' => 'guest',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
