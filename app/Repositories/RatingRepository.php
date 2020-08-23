<?php namespace App\Repositories;

use App\Models\Book;
use App\Models\Rating;
use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class RatingRepository implements RepositoryInterface
{
    public function all(){
    }

    public function create(array $data){
        $request = $data[0];
        return Rating::create([
            'book_id'=>$request->input('book_id'),
            'value'=>$request->input('value'),
            'created_by'=>JWTAuth::user()->username
        ]);
    }

    public function update(array $data, $id){
        $request = $data[0];
        return Rating::where('id', $id)->update(array(
            'value' => $request->input('value'),
            'updated_by' => JWTAuth::user()->username
        ));
    }

    public function delete($id){
        Rating::destroy($id);
    }

    public function show($id){
        return Rating::find($id);
    }
}
