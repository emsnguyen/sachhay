<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $bookId = $request->bookId;
        $comments = Comment::where('book_id', $bookId)->get();
        $this->sendResponse($comments, null);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bookCreator = Book::find($request->book_id)->created_by;
        
        // authorize 
        // $response = Gate::check('add-comment', Auth::user(), $bookCreator);
        if (!Gate::allows('add-comment', [$bookCreator]))  {
            $this->sendError('You are not authorized to add comment', null, 500);
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
        $this->sendResponse($comment, 'Comment saved');
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
        // authorize 
        if (!Gate::allows('update-comment', [$comment])) {
            $this->sendError('You are not authorized to update this comment', null, 500);
        } 
        $comment = Comment::find($id);
        $comment->content = $request->content;
        $comment->updated_by = Auth::user()->name;
        $comment->save();
        $this->sendResponse($comment, 'Comment updated');
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
        // authorize
        if (!Gate::allows('delete-comment', [$comment])) {
            $this->sendError('You are not authorized to delete this comment', null, 500);
        } 
        $comment->delete();
        $this->sendResponse($comment, 'Comment deleted');
    }
}
