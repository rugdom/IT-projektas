<?php

use Illuminate\Database\Seeder;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
        	'name' => 'Jonas Jonaitis', 
        	'email' => 'jonas@gmail.com',
        	'password' => bcrypt('jonas123')
        ]);

        $role = Role::create(['name' => 'Paprastas vartotojas']);

        $permissions = Permission::where('name', 'event-order')->
            pluck('id','id');

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}
