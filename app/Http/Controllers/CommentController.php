<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Comment;
use App\Repositories\CommentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Tymon\JWTAuth\Facades\JWTAuth;

class CommentController extends Controller
{
    protected $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
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
        if (!Gate::allows('add-comment', [$bookCreator]))  {
            $this->sendError('You are not authorized to add comment', null, 500);
        }
        // validate form data
        $request->validate([
            'content' => 'required|min:3|max:255'
        ]);
        $comment = $this->commentRepository->create([$request]);
        return $comment;
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
        $comment = $this->commentRepository->show($id);
        // authorize
        if (!Gate::allows('update-comment', [$comment])) {
            $this->sendError('You are not authorized to update this comment', null, 500);
        }
        $comment = $this->commentRepository->update([$request->all()], $id);
        return $comment;
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
