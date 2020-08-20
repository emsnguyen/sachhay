<?php namespace App\Repositories;

use App\Models\Book;
use App\Models\Comment;
use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class CommentRepository implements RepositoryInterface
{
    public function all(){
    }

    public function create(array $data){
        $request = $data[0];
        $comment = Comment::create([
            'book_id'=> $request->get('book_id'),
            'content'=> $request->get('content'),
            'created_by'=> JWTAuth::user()->username
        ]);
        return $comment;
    }

    public function update(array $data, $id){
    }

    public function delete($id){
    }

    public function show($id){
    }
}
