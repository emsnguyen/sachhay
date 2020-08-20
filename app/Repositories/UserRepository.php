<?php namespace App\Repositories;

use App\User;

class UserRepository implements RepositoryInterface
{
    public function all(){
    }

    public function create(array $data){
        $request = $data[0];
        User::create([
            'name' => $request->get('name'),
            'username'=> $request->get('username'),
            'email' => $request->get('email'),
            'password'=> bcrypt($request->get('password')),
        ]);
        $user = User::first();
        return $user;
    }

    public function update(array $data, $id){
    }

    public function delete($id){
    }

    public function show($id){
    }
}
