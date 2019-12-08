<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Type;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
        	'name' => 'Administratorius',
        	'email' => 'admin@gmail.com',
        	'password' => bcrypt('123456'),
            'specialization' => 'Pramoginiai renginiai'
        ]);

        $role = Role::create(['name' => 'Administratorius']);
        $type = Type::create(['name' => 'Pramoginiai renginiai']);


        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}
