<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $this->call(CategorySeeder::class);

        $admin =   \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);

        $subadmin =  \App\Models\User::create([
            'name' => 'subadmin',
            'email' => 'subadmin@admin.com',
            'password' => bcrypt('Pass@123'),
        ]);

        $adminRole = Role::create(['name' => 'admin']);
        $subadminRole = Role::create(['name' => 'subadmin']);

        $admin->assignRole($adminRole);
        $subadmin->assignRole($subadminRole);

        Permission::create(['name' => 'view products']);
        Permission::create(['name' => 'create products']);
        Permission::create(['name' => 'edit products']);
        Permission::create(['name' => 'delete products']);

        Permission::create(['name' => 'view category']);
        Permission::create(['name' => 'create category']);
        Permission::create(['name' => 'edit category']);
        Permission::create(['name' => 'delete category']);

        $adminRole->givePermissionTo([
            'view products', 'create products', 'edit products', 'delete products',
            'view category', 'create category', 'edit category', 'delete category',
        ]);

        $subadminRole->givePermissionTo([
            'view products', 'create products',
            'view category', 'create category'
        ]);
    }
}
