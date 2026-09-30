<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Admin Account
        |--------------------------------------------------------------------------
        */

        $adminEmail = config('demo.admin.email');
        $adminPassword = config('demo.admin.password');

        if (!$adminEmail || !$adminPassword) {
            throw new RuntimeException(
                'Demo admin email/password are missing from .env'
            );
        }

        $admin = User::updateOrCreate(
            [
                'email' => $adminEmail,
            ],
            [
                'name' => config('demo.admin.name'),
                'phone' => '012345678',
                'shipping_address' => 'Phnom Penh, Cambodia',
                'province' => 'Phnom Penh',
                'password' => Hash::make($adminPassword),
            ]
        );

        $admin->role = 'admin';
        $admin->save();


        /*
        |--------------------------------------------------------------------------
        | Customer Account
        |--------------------------------------------------------------------------
        */

        $customerEmail = config('demo.customer.email');
        $customerPassword = config('demo.customer.password');

        if (!$customerEmail || !$customerPassword) {
            throw new RuntimeException(
                'Demo customer email/password are missing from .env'
            );
        }

        $customer = User::updateOrCreate(
            [
                'email' => $customerEmail,
            ],
            [
                'name' => config('demo.customer.name'),
                'phone' => '012345678',
                'shipping_address' => 'Phnom Penh, Cambodia',
                'province' => 'Phnom Penh',
                'password' => Hash::make($customerPassword),
            ]
        );

        $customer->role = 'customer';
        $customer->save();
    }
}