<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Api Logger',
            'email' => env('USER_EMAIL'),
            'password' => bcrypt(str_random(15)),
            'api_token' => str_random(60),
        ]);
    }
}
