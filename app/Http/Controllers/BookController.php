<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Image;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
        
        // save to book table
        $book->save();

        // save to image table
        $image = new Image();
        $image->url = 'bookcovers/'.$fileName;
        $image->book_id = $book->id;
        $image->save();

        return $this->show($book->id);
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = Book::find($id);
        return view('dashboard/bookEdit')->with('book', $book);
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
        $request->validate([
            'title' => 'bail|required|max:255',
            'author' => 'required|max:255',
            'isbn' => 'unique:books|max:255',
            'isbn' => [
                'required',
                'unique:books,isbn,' . $id
            ],
            'author' => 'required|max:255',
            'review' => 'required|max:10000',
            'file'=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        // get back the object to update
        $book = Book::find($id);
        $book->title=$request->title;
        $book->author=$request->author;
        $book->publisher=$request->publisher;
        $book->review=$request->review;
        $book->updated_by='Fake update user';
        // save update on books table
        $book->save();

        // update image if it has been changed
        if ($request->isImageUpdated) {
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
        
        // // delete
        // foreach ($request->deletedImages as $delete) {
        //     // delete from storage
        //     Storage::delete($delete);
        //     // delete old image from images table, given id
        //     Image::destroy($delete);
        // }
        // $imagesToInsert = [];
        // // add new image
        // foreach ($request->addedImages as $add) {
        //     // save to disk and get back file path
        //     $path = $request->file($add)->store('bookcovers');
        //     // save to images table 
        //     $newImage = new Image();
        //     $newImage->url=$path;
        //     $newImage->book_id = $book->id;
        //     $imagesToInsert.array_push($newImage);
        // }
        // Image::insert($imagesToInsert);
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
        // Only allow when no rating and comment is added yet
        $book = Book::find($id);
        $errors = array();
        if (!is_null($book->comment) || !is_null($book->ratings)) {
            array_push($errors, 'You are not authorized to delete this book because there are already comments and ratings for it');
            return back()->withErrors($errors);
        }
        Book::destroy($book);
        return redirect('dashboard/books')->with('success', 'Successfully deleted your reservation!');
    }

    public function search(Request $request)
    {
        $query = $request->q;
        // search like in eloquent laravel
        $books = Book::where('title', 'like', '%'.$query.'%')
        ->orWhere('author', 'like', '%'.$query.'%')->get();
        return view('dashboard/books')->with('books', $books);
    }
}