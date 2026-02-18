<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('nama_role', 'admin')->first();

        User::firstOrCreate(
            ['nip' => '198001012006041001'],
            [
                'name' => 'Keisha Azzahra',
                'password' => Hash::make('Admin#12345'),
                'role_id' => $adminRole->id,
            ]
        );
    }
}
