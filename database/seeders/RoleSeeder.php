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
        $role3 = Role::create(['name' => 'anonymous']);

        //Passport Routes
        Permission::create(['name' => 'api.register']);
        Permission::create(['name' => 'api.login']);

        //Admin Route
        Permission::create(['name' => 'api.players.index']);

        //User Routes
        Permission::create(['name' => 'api.players.show']);
        Permission::create(['name' => 'api.players.store']);
        Permission::create(['name' => 'api.players.update']);
        Permission::create(['name' => 'api.players.destroy']);
        Permission::create(['name' => 'api.logout']);

        //General Routes
        Permission::create(['name' => 'api.players.generalRanking']);
        Permission::create(['name' => 'api.players.winnerRanking']);
        Permission::create(['name' => 'api.players.loserRanking']);


    }
}
