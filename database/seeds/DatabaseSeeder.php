<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
    	$user = new User();
    	$user->firstname = 'Admin';
    	$user->lastname = 'Admin';
    	$user->username = 'admin';
    	$user->password = bcrypt('123456');
    	$user->gender = 'MALE';
    	$user->mobile_number = '09223421234';
    	$user->login_type = 'ADMIN';
    	$user->status = 'ENABLE';
    	$user->save();
    }
}
