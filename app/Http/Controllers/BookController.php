<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Tymon\JWTAuth\Facades\JWTAuth;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $books = Book::with('comments', 'ratings', 'images')->get();
        return $this->sendResponse($books, "Available books");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate form data
        $request->validate([
            'title' => 'bail|required|max:255',
            'author' => 'required|max:255',
            'isbn' => 'unique:books|max:255',
            'author' => 'required|max:255',
            'review' => 'required|max:10000',
            'file'=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        $fileName = time().'.'.$request->file->extension();
        $request->file->move(public_path().'/bookcovers/', $fileName);

        // create object for saving
        $book = new Book();
        $book->title = $request->title;
        $book->isbn = $request->isbn;
        $book->author = $request->author;
        $book->publisher = $request->publisher;
        $book->review = $request->review;
        $book->created_by = JWTAuth::user()->username;

        // save to book table
        $book->save();

        // save to image table
        $image = new Image();
        $image->url = 'bookcovers/'.$fileName;
        $image->book_id = $book->id;
        $image->save();

        return $this->sendResponse($book, "Book created");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::with('comments', 'ratings', 'images')->where('id', '=', $id)->get()->first();
        return $this->sendResponse($book, "Book detail");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $errors = array();
        // get back the object to update
        $book = Book::find($id);
        // authorize
        if (!Gate::allows('update-book', [$book])) {
            $this->sendError('You are not authorized to edit this book', null, 500);
        }

        // validate form data
        $request->validate([
            'title' => 'bail|required|max:255',
            'author' => 'required|max:255',
            'isbn' => 'unique:books|max:255',
            'isbn' => [
                'required',
                'unique:books,isbn,' . $id
            ],
            'author' => 'required|max:255',
            'review' => 'required|max:10000'
            // 'file'=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        $book->title=$request->title;
        $book->author=$request->author;
        $book->publisher=$request->publisher;
        $book->review=$request->review;
        $book->updated_by=JWTAuth::user()->username;
        // save update on books table
        $book->save();

        // update image if it has been changed
        if ($request->isImageUpdated == "1") {
            // delete old image
            $imageIds = array();
            foreach($book->images as $image) {
                array_push($imageIds, $image->id);
            }
            $imageToDelete= Image::whereIn('id', $imageIds)->first();
            $oldUrl = "";
            // delete from image table
            if ($imageToDelete !== null) {
                $oldUrl = $imageToDelete->url;
                $imageToDelete->delete();
                // delete from public path (local storage)
                File::delete($oldUrl);
            }

            // save new image
            // save to local storage
            $fileName = time().'.'.$request->file->extension();
            $request->file->move(public_path().'/bookcovers/', $fileName);

            // save to image table
            $image = new Image();
            $image->url = 'bookcovers/'.$fileName;
            $image->book_id = $book->id;
            $image->save();
        }
        return $this->show($book->id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::find($id);
        $deletedBook = $book;
        // Authorize
        if (!Gate::allows('delete-book', [$book])) {
            $this->sendError('You are not authorized to delete this book', null, 500);
        }
        if (count($book->comments) > 0 || count($book->ratings) > 0) {
            $this->sendError('This book cannot be deleted because there are already comments and ratings for it', null, 500);
        }
        $book->delete();
        $this->sendResponse($deletedBook, 'Successfully deleted your book!');
    }

    public function search(Request $request)
    {
        $query = $request->q;
        $books = Book::where('title', 'like', '%'.$query.'%')->orWhere('author', 'like', '%'.$query.'%')->get();
        $this->sendResponse($books, 200);
    }
}
