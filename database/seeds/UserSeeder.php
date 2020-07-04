<?php

use App\Models\Book;
use App\User;
use Illuminate\Database\Seeder;

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
            ['id'=>1, 'name'=>'admin', 'email'=>'hongmienft98@gmail.com','password'=>'admin', 'role'=>1, 'banned'=>false],
            ['id'=>2, 'name'=>'user', 'email'=>'hongmienft98@gmail.com','password'=>'user', 'role'=>2, 'banned'=>false],
        ];
        foreach ($users as $user) { 
            User::create(array('id'=> $user['id'], 'name' => $user['name'], 'email' => $user['email'], 
            'password' => $user['password'], 'role' => $user['role'], 'banned'=> $user['banned']
        )); 
        } 
    }
}
