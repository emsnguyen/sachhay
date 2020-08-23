<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Services\CommentService;

class CommentController extends Controller
{
    protected $commentService;
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateCommentRequest $request
     * @return void
     * @throws \Exception
     */
    public function store(CreateCommentRequest $request)
    {
        $comment = $this->commentService->create($request);
        return $this->sendResponse($comment, 'Comment added');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCommentRequest $request
     * @param int $id
     * @return void
     * @throws \Exception
     */
    public function update(UpdateCommentRequest $request, $id)
    {
        $comment = $this->commentService->update($request, $id);
        return $this->sendResponse($comment, 'Comment updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     */
    public function destroy($id)
    {
        return $this->sendResponse($id, 'Comment deleted');
    }
}
