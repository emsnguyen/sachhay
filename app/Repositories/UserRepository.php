<?php namespace App\Repositories;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserRepository implements RepositoryInterface
{
    public function all(){
        return User::all();
    }

    public function allWithPagingAndSorting(Request $request){
        $size = $request->input('size');
        $sortField = $request->input('sortField');
        $sortDirection = $request->input('sortDirection');
        dd($sortDirection);
        return User::orderBy($sortField, $sortDirection)->paginate($size);
    }

    public function create(array $data){
        $request = $data[0];
        User::create([
            'name' => $request->get('name'),
            'username'=> $request->get('username'),
            'email' => $request->get('email'),
            'password'=> bcrypt($request->get('password')),
        ]);
        return User::first();
    }

    public function update(array $data, $id){
        $request = $data[0];
        User::where('id', '=', $id)->update(
            array(
                "name"=>$request->input('name'),
                "email"=>$request->input('email'),
                "banned"=>$request->input('banned'),
                "role"=>$request->input('role'),
            )
        );
    }

    public function delete($id){
        User::destroy($id);
    }

    public function show($id){
        User::find($id);
    }
}
