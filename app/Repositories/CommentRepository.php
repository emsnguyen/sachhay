<?php namespace App\Repositories;

use App\Models\Comment;
use Tymon\JWTAuth\Facades\JWTAuth;

class CommentRepository implements RepositoryInterface
{
    public function all(){
    }

    public function create(array $data){
        $request = $data[0];
        return Comment::create([
            'book_id'=> $request->input('book_id'),
            'content'=> $request->input('content'),
            'created_by'=> JWTAuth::user()->username
        ]);
    }

    public function update(array $data, $id){
        $request = $data[0];
        return Comment::find($id)->update([
            'content'=> $request->input('content'),
            'updated_by'=> JWTAuth::user()->username
        ]);
    }

    public function delete($id){
        Comment::destroy($id);
    }

    public function show($id){
        return Comment::find($id);
    }
}
