<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $permissions_admin = [


            'role-table',
            'role-add',
            'role-edit',
            'role-delete',

            'employee-table',
            'employee-add',
            'employee-edit',
            'employee-delete',

            'user-table',
            'user-add',
            'user-edit',
            'user-delete',


            'order-table',
            'order-add',
            'order-edit',
            'order-delete',

            'notification-table',
            'notification-add',
            'notification-edit',
            'notification-delete',

            'setting-table',
            'setting-add',
            'setting-edit',
            'setting-delete',

            'category-table',
            'category-add',
            'category-edit',
            'category-delete',

            'product-table',
            'product-add',
            'product-edit',
            'product-delete',

            'offer-table',
            'offer-add',
            'offer-edit',
            'offer-delete',

            'coupon-table',
            'coupon-add',
            'coupon-edit',
            'coupon-delete',

            'currency-table',
            'currency-add',
            'currency-edit',
            'currency-delete',


        ];

         foreach ($permissions_admin as $permission_ad) {
            Permission::create(['name' => $permission_ad, 'guard_name' => 'admin']);
        }
    }
}
