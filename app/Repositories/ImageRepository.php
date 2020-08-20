<?php namespace App\Repositories;

use App\Models\Book;
use App\Models\Image;
use App\User;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class ImageRepository implements RepositoryInterface
{
    public function all(){
    }

    public function create(array $data){
        $request = $data[0];
        return Image::create([
            'book_id' => $request->book_id,
            'url' => $request->url
        ]);
    }

    public function update(array $data, $id){
    }

    public function delete($id){
        return Image::where('id', $id)->delete();
    }

    public function show($id){
    }

    public function findByIdIn(array $imageIds)
    {
        return Image::whereIn('id', $imageIds)->first();
    }
}
