<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Type;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateVipUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
        	'name' => 'Petras Petraitis',
        	'email' => 'petras@gmail.com',
        	'password' => bcrypt('petras123'),
            'specialization' => 'Sporto varžybos'
        ]);

        $role = Role::create(['name' => 'VIP vartotojas']);
        $type = Type::create(['name' => 'Sporto varžybos']);

        $permissions = Permission::where('name', 'event-order')->
            orWhere('name', 'event-create')->pluck('id','id');

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}
