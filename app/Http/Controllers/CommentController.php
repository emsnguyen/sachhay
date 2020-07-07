<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
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
        $comments = Comment::where('book_id', $bookId)->get();
        return $comments;
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
        
        // authorize 
        // $response = Gate::check('add-comment', Auth::user(), $bookCreator);
        if (!Gate::allows('add-comment', [$bookCreator]))  {
            array_push($errors, 'You are not authorized to add comment');
            return back()->withErrors($errors);
        } 
        // validate form data
        $request->validate([
            'content' => 'required|max:255'
        ]);
        $comment = new Comment();
        $comment->book_id = $request->book_id;
        $comment->content = $request->content;
        $comment->created_by = Auth::user()->name;
        $comment->save();
        return $comment;
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
        // get back the object to update
        $comment = Comment::find($id);
        $errors = array();
        // authorize 
        $response = Gate::check('update-comment', Auth::user(), $comment);
        if (!$response) {
            array_push($errors, 'You are not authorized to edit this comment');
            return back()->withErrors($errors);
        } 
        $comment = Comment::find($id);
        $comment->content = $request->content;
        $comment->updated_by = Auth::user()->name;
        $comment->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // get back the object to delete
        $comment = Comment::find($id);
        $errors = array();
        // authorize
        if (!Gate::allows('delete-comment', [$comment])) {
            array_push($errors, 'You are not authorized to delete this comment');
            return back()->withErrors($errors);
        } 
        $comment->delete();
    }
}
