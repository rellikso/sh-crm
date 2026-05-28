<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Clearing Spatie's permissions cache (critical on restarts)
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Creating roles if they don't exist yet.
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);

        // 2. Creating a default administrator for the first login
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@' . env('APP_DOMAIN')],
            [
                'name' => 'Главный Администратор',
                'password' => Hash::make('secret123'), // We'll replace it with a safe one in production.
            ]
        );

        // Assigning a role to a user
        $adminUser->assignRole($adminRole);

        $this->command->info('Базовые роли и аккаунт admin@' . env('APP_DOMAIN') . ' успешно созданы.');
    }
}
