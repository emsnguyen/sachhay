<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Image;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
    public function create()
    {
        return view('dashboard/bookCreate');
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
            // 'isbn' => 'required|max:255',
            'author' => 'required|max:255',
            'review' => 'required|max:10000',
            'file'=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        $fileName = time().'.'.$request->file->extension();  
        $request->file->move(public_path().'/bookcovers/', $fileName);
        // Storage::disk('public')->put($fileName, $request->file);
        $url = Storage::url($fileName);
   
        // return back()
        //     ->with('success','You have successfully upload file.')
        //     ->with('file',$fileName);

        // create object for saving
        $book = new Book();
        $book->title = $request->title;
        $book->isbn = $request->isbn;
        $book->author = $request->author;
        $book->publisher = $request->publisher;
        $book->review = $request->review;
        
        // save to book table
        $book->save();

        // save to image table
        $image = new Image();
        $image->url = 'bookcovers/'.$fileName;
        $image->book_id = $book->id;
        $image->save();

        return view('dashboard/bookSingle')->with('book', $book);
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
        $request->validate([
            'images' => 'required|mimes:png,jpg,jpeg|max:2048',
        ]);

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
        // validate form data
        $validatedData = $request->validate([
            'title' => 'bail|required|max:255',
            'author' => 'required|max:255',
            'isbn' => 'unique:books|max:255',
            'author' => 'required|max:255',
            'review' => 'required|max:10000',
        ]);
        // get back the object to update
        $book = Book::find($id);
        $book->title=$request->title;
        $book->author=$request->author;
        $book->publisher=$request->publisher;
        $book->review=$request->review;
        // save update on books table
        $book->save();
        
        // delete
        foreach ($request->deletedImages as $delete) {
            // delete from storage
            Storage::delete($delete);
            // delete old image from images table, given id
            Image::destroy($delete);
        }
        $imagesToInsert = [];
        // add new image
        foreach ($request->addedImages as $add) {
            // save to disk and get back file path
            $path = $request->file($add)->store('bookcovers');
            // save to images table 
            $newImage = new Image();
            $newImage->url=$path;
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
        // Only allow when no rating and comment is added yet
        $book = Book::find($id);
        if (!empty($book->comment) || !empty($book->ratings)) {
            return "Cannot delete";
        }
        Book::destroy($book);
        return view('dashboard');
    }
}