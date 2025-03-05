<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $admin = Role::create(['name' => 'Admin']);

        $superAdmin->givePermissionTo([
            'view-role',
            'create-role',
            'edit-role',
            'delete-role',
            'create-user',
            'edit-user',
            'delete-user',
            'view-user',
            'view-video',
            'create-video',
            'edit-video',
            'delete-video',
        ]);

        $admin->givePermissionTo([
            'view-role',
            'create-role',
            'edit-role',
            'delete-role',
            'create-user',
            'edit-user',
            'delete-user',
            'view-user',
            'view-video',
            'create-video',
            'edit-video',
            'delete-video',
        ]);
    }
}
