<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
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
        // authorize 
        $response = Gate::check('add-book', Auth::user());
        if (!$response) {
            array_push($errors, 'You are not authorized to create book');
            return back()->withErrors($errors);
        }
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
        $book->created_by=Auth::user()->name;
        
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
        $errors = array();
        $book = Book::find($id);
        // authorize 
        // $response = Gate::check('update-book', [$book]);
        if (!Gate::check('update-book', [Auth::user(), $book])) {
            // array_push($errors, 'You are not authorized to edit this book');
            // return back()->withErrors($errors);
        } 
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
        $errors = array();
        // get back the object to update
        $book = Book::find($id);
        // authorize 
        // $book->created_by="HKT";
        // $response = Gate::check('update-book', [Auth::user(), $book]);
        if (!Gate::allows('update-book', [$book])) {
            array_push($errors, 'You are not authorized to edit this book');
            return back()->withErrors($errors);
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
            'review' => 'required|max:10000',
            'file'=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        $book->title=$request->title;
        $book->author=$request->author;
        $book->publisher=$request->publisher;
        $book->review=$request->review;
        $book->updated_by=Auth::user()->name;
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
        $errors = array();
        // Authorize
        // $response = Gate::check('delete-book', Auth::user(), $book);
        if (!Gate::allows('delete-book', [$book])) {
            array_push($errors, 'You are not authorized to delete this book');
            return back()->withErrors($errors);
        }   
        if (count($book->comments) > 0 || count($book->ratings) > 0) {
            array_push($errors, 'This book cannot be deleted because there are already comments and ratings for it');
            return back()->withErrors($errors);
        }
        $book->delete();
        return redirect('dashboard/books')->with('success', 'Successfully deleted your book!');
    }

    public function search(Request $request)
    {
        $query = $request->q;
        // search like in eloquent laravel
        $books = Book::where('title', 'like', '%'.$query.'%')->orWhere('author', 'like', '%'.$query.'%')->get();
        return view('dashboard/books')->with('books', $books);
    }
}
