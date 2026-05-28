<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /*
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        */

        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);

        // 1. Core Authorization Scaffolding
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);

        // 2. Provision Staff/Manager User
        $manager = User::firstOrCreate(
            ['email' => 'manager@techsms.test'],
            [
                'name' => 'Default Manager',
                'password' => 'password', // Single-hashed automatically via Eloquent attribute casting
            ]
        );

        $manager->assignRole($managerRole);

        // 3. Provision Customers with Inbound Support Tickets
        // Create 5 customers who have exactly 1 ticket
        Customer::factory()
            ->count(5)
            ->has(Ticket::factory()->count(1))
            ->create();

        // Create 3 heavy-interaction customers with multiple tickets
        Customer::factory()
            ->count(3)
            ->has(Ticket::factory()->count(3))
            ->create();

        // Create 2 silent customers with no tickets yet
        Customer::factory()
            ->count(2)
            ->create();
    }
}
