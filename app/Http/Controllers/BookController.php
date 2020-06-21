<?php

namespace App\Http\Controllers;

use App\Models\Book;
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
        // save to image table
        foreach($images as $i) {
            $image = new Image();
            $image->url = $i->url;
            $image->book_id = $book->id;
            $image->save();
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
        return view('dashboard/bookSingle')->with('book', $book);
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
            // delete old image from images table, given id
            Image::destroy($delete);
        }
        $imagesToInsert = [];
        // add new image
        foreach ($request->addedImages as $add) {
            // save to images table 
            $newImage = new Image();
            $newImage->url=$add;
            $newImage->book_id = $book->id;
            $imagesToInsert.array_push($newImage);
        }
        Image::insert($imagesToInsert);
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
