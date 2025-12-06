<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateUserRolesSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Update admin role
        DB::table('users')
            ->where('email', 'admin@example.com')
            ->update(['role' => 'admin']);

        // Update all other users to mahasiswa
        DB::table('users')
            ->where('email', '!=', 'admin@example.com')
            ->update(['role' => 'mahasiswa']);

        $this->command->info('User roles updated successfully!');
    }
}
