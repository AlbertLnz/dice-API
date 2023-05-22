<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role1 = Role::create(['name' => 'admin']);
        $role2 = Role::create(['name' => 'client']); //normal user

        //Passport Routes
        Permission::create(['name' => 'api.register'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'api.login'])->syncRoles([$role1, $role2]);

        //Admin Route
        Permission::create(['name' => 'api.players.index'])->assignRole($role1);

        //User Routes
        Permission::create(['name' => 'api.players.show'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'api.players.store'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'api.players.update'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'api.players.destroy'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'api.logout'])->syncRoles([$role1, $role2]);

        //General Routes
        Permission::create(['name' => 'api.players.generalRanking']);
        Permission::create(['name' => 'api.players.winnerRanking']);
        Permission::create(['name' => 'api.players.loserRanking']);


    }
}
