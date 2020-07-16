<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RatingController extends Controller
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
    public function index(Request $request)
    {
        $bookId = $request->bookId;
        $ratings = Rating::where('book_id', $bookId)->get();
        return $ratings;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $errors = array();
        $bookCreator = Book::find($request->book_id)->created_by;
        $user = Auth::user();
        // authorize 
        $response = Gate::allows('add-rating', [$bookCreator]);
        if (!$response) {
            array_push($errors, 'You are not authorized to add rating');
            return back()->withErrors($errors);
        } 
        // validate form data
        $request->validate([
            'value' => 'required'
        ]);
        $rated = Rating::where('book_id', "=", $request->book_id)->where('created_by', "=", $user->name)->first();
        // nếu đã có rated thì chỉ update thôi
        if ($rated != null) {
            $rated->value = $request->value;
            $rated->save();
            return $rated;
        }
        $rating = new Rating();
        $rating->book_id = $request->book_id;
        $rating->value = $request->value;
        $rating->created_by = Auth::user()->name;
        $rating->save();
        return $rating;        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $rating = Rating::find($id);
        $errors = array();
        // authorize 
        $response = Gate::check('update-rating', Auth::user(), $rating);
        if (!$response) {
            array_push($errors, 'You are not authorized to update this rating');
            return back()->withErrors($errors);
        } 

        $rating->value = $request->value;
        $rating->updated_by = Auth::user()->name;
        $rating->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rating = Rating::find($id);
        $errors = array();
        // authorize 
        $response = Gate::check('delete-rating', Auth::user(), $rating);
        if (!$response) {
            array_push($errors, 'You are not authorized to delete this rating');
            return back()->withErrors($errors);
        } 
        $rating->delete();
    }
}
