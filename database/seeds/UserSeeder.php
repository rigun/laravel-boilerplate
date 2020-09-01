<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'superadministrator',
                'rolename' => 'superadministrator',
                'username' => 'superadministrator',
            ],
            [
                'name' => 'administrator',
                'rolename' => 'administrator',
                'username' => 'administrator',
            ],
            [
                'name' => 'customer',
                'rolename' => 'customer',
                'username' => 'customer',
            ]
        ];
        
        foreach($users as $user){
            $item = new User();
            $item->name = $user['name'];
            $item->username = $user['username'];
            $item->password = bcrypt($user['username']);
            $role = Role::where('name',$user['rolename'])->first();
            $item->role_id = $role->id;
            $item->createdBy = $user['username'];
            $item->save();
        }
    }
}
