<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookImage;
use App\Models\Image;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::all();
        // get image from book image table
        return view('dashboard/books')->with('books', $books);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $book = Book::find($id);
        return view('dashboard/bookCreate', compact('book'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // TODO: add validation
        $book = new Book();
        $book->title = $request->title;
        $book->isbn = $request->isbn;
        $book->author = $request->author;
        $book->publisher = $request->publisher;
        $book->review = $request->review;
        
        // save to book table
        $book->save();
        $images = $request->images;
        // save to image and book_image table
        foreach($images as $i) {
            $image = new Image();
            $image->url = $i->url;
            $image->save();

            $bookImage = new BookImage();
            $bookImage->book_id = $book->id;
            $bookImage->image_id = $image->id;
            $bookImage->save();
        }
        return view('dashboard/bookSingle', compact('book', 'images'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::find($id);
        $images = BookImage::where('book_id',$id)->get();
        return view('dashboard/bookDetail', compact('book', 'images'));
    }

    public function upload(Request $request)
    {
        $path = $request->file('file')->store('files');
        // return to book create or book edit page
        return view('dashboard/bookCreate', compact('path'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = Book::find($id);
        $images = BookImage::where('book_id',$id)->get();
        return view('dashboard/bookEdit', compact('book', 'images'));
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
        // TODO: add form validation
        $book = Book::find($id);
        $book->title=$request->title;
        $book->author=$request->author;
        $book->publisher=$request->publisher;
        $book->review=$request->review;
        // save update on books table
        $book->save();
        
        // delete
        foreach ($request->deletedImages as $delete) {
            // delete from book_images table    
            BookImage::where('image_id', $delete)->delete();
            // delete old image from images table, given id
            Image::destroy($delete);
        }

        // add new image
        foreach ($request->addedImages as $add) {
            // save to images table 
            $newImage = new Image();
            $newImage->url=$add;
            $newImage->save();
            // save to book_image table
            $bookImage = new BookImage();
            $bookImage->book_id = $book->id;
            $bookImage->image_id = $newImage->id;
            $bookImage->save();
        }
        Image::insert($request->addedImages);

        return view('dashboard/bookEdit', compact('book'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //TODO: add validation, only allow when no rating and comment is added yet
        $books = Book::find($id);
        Book::destroy($books);
        return view('dashboard');
    }
}
