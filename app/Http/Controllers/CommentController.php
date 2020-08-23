<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Services\CommentService;
use Illuminate\Http\Response;

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
     * @return Response
     * @throws \Exception
     */
    public function store(CreateCommentRequest $request)
    {
        return $this->commentService->create($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCommentRequest $request
     * @param int $id
     * @return Response
     * @throws \Exception
     */
    public function update(UpdateCommentRequest $request, $id)
    {
        return $this->commentService->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     */
    public function destroy($id)
    {
        $this->sendResponse($id, 'Comment deleted');
    }
}
