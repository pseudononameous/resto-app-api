<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'View Orders', 'slug' => 'orders.view', 'group' => 'orders'],
            ['name' => 'Manage Orders', 'slug' => 'orders.manage', 'group' => 'orders'],
            ['name' => 'View Menu Items', 'slug' => 'menu-items.view', 'group' => 'menu'],
            ['name' => 'Manage Menu Items', 'slug' => 'menu-items.manage', 'group' => 'menu'],
            ['name' => 'View Products', 'slug' => 'products.view', 'group' => 'products'],
            ['name' => 'Manage Products', 'slug' => 'products.manage', 'group' => 'products'],
            ['name' => 'View Inventory', 'slug' => 'inventory.view', 'group' => 'inventory'],
            ['name' => 'Manage Inventory', 'slug' => 'inventory.manage', 'group' => 'inventory'],
            ['name' => 'View Customers', 'slug' => 'customers.view', 'group' => 'customers'],
            ['name' => 'Manage Customers', 'slug' => 'customers.manage', 'group' => 'customers'],
            ['name' => 'View Users', 'slug' => 'users.view', 'group' => 'users'],
            ['name' => 'Manage Users', 'slug' => 'users.manage', 'group' => 'users'],
            ['name' => 'Manage Roles', 'slug' => 'roles.manage', 'group' => 'users'],
            ['name' => 'View Settings', 'slug' => 'settings.view', 'group' => 'settings'],
            ['name' => 'Manage Settings', 'slug' => 'settings.manage', 'group' => 'settings'],
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(
                ['slug' => $p['slug']],
                ['name' => $p['name'], 'group' => $p['group'], 'description' => null]
            );
        }

        $admin = Role::firstOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Admin', 'description' => 'Full access']
        );
        $admin->permissions()->sync(Permission::pluck('id'));

        $manager = Role::firstOrCreate(
            ['slug' => 'manager'],
            ['name' => 'Manager', 'description' => 'Manage orders and menu']
        );
        $manager->permissions()->sync(
            Permission::whereIn('slug', [
                'orders.view', 'orders.manage', 'menu-items.view', 'menu-items.manage',
                'products.view', 'inventory.view', 'inventory.manage', 'customers.view', 'customers.manage',
            ])->pluck('id')
        );

        $staff = Role::firstOrCreate(
            ['slug' => 'staff'],
            ['name' => 'Staff', 'description' => 'View and process orders']
        );
        $staff->permissions()->sync(
            Permission::whereIn('slug', ['orders.view', 'orders.manage', 'menu-items.view', 'customers.view'])->pluck('id')
        );
    }
}
