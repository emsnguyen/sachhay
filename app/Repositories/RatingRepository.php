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
        $rating = Rating::create([
            'title' => $request->get('title'),
            'isbn'=> $request->get('isbn'),
            'author'=> $request->get('author'),
            'publisher'=> $request->get('publisher'),
            'review'=> $request->get('review'),
        ]);
        return $rating;
    }

    public function update(array $data, $id){
    }

    public function delete($id){
    }

    public function show($id){
    }
}
