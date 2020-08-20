<?php namespace App\Repositories;

use App\Models\Book;
use Tymon\JWTAuth\Facades\JWTAuth;

class BookRepository implements RepositoryInterface
{
    public function all()
    {
        return Book::with('comments', 'ratings', 'images')->get();
    }

    public function create(array $data)
    {
        $request = $data[0];
        $book = Book::create([
            'title' => $request->get('title'),
            'isbn' => $request->get('isbn'),
            'author' => $request->get('author'),
            'publisher' => $request->get('publisher'),
            'review' => $request->get('review'),
            'created_by' => JWTAuth::user()->username,
        ]);
        return $book;
    }

    public function update(array $data, $id)
    {
        $request = $data[0];
        $book = Book::where('id', $id)
            ->update([
                'title' => $request->title,
                'isbn' => $request->isbn,
                'author' => $request->author,
                'publisher' => $request->publisher,
                'review' => $request->review,
                'updated_by' => JWTAuth::user()->username,
            ]);
        return $book;
    }

    public function delete($id)
    {
        $book = Book::find($id);
        $book->delete();
    }

    public function show($id)
    {
        return Book::with('comments', 'ratings', 'images')
            ->where('id', '=', $id)
            ->get()->first();
    }
}
