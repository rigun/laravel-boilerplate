<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'superadministrator',
            ],
            [
                'name' => 'administrator',
            ],
            [
                'name' => 'customer',
            ]
        ];
        foreach($roles as $role){
            $item = new Role();
            $item->createdBy = 'superadministrator';
            $item->name = $role["name"];
            $item->save();
        }
    }
}
