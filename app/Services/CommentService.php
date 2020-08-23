<?php namespace App\Services;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Repositories\BookRepository;
use App\Repositories\CommentRepository;
use Illuminate\Support\Facades\Gate;
use mysql_xdevapi\Exception;

class CommentService
{
    protected $commentRepository;
    protected $bookRepository;
    public function __construct(CommentRepository $commentRepository,
                                BookRepository $bookRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->bookRepository = $bookRepository;
    }

    /**
     * @param UpdateCommentRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function create(UpdateCommentRequest $request) {
        $book = $this->bookRepository->show($request->input('book_id'));

        $bookCreator = $book->created_by;

        // authorize
        if (!Gate::allows('add-comment', [$bookCreator]))  {
            throw new \Exception('You are not authorized to add comment', 500);
        }
        return $this->commentRepository->create([$request]);
    }

    /**
     * @param UpdateCommentRequest $request
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function update(UpdateCommentRequest $request, $id) {
        // get back the object to update
        $comment = $this->show($id);
        // authorize
        if (!Gate::allows('update-comment', [$comment])) {
            throw new \Exception('You are not authorized to update this comment', 500);
        }
        return $this->commentRepository->update([$request], $id);
    }

    /**
     * @param int $id
     */
    public function delete(int $id)
    {
        $comment = $this->show($id);
        // authorize
        if (!Gate::allows('delete-comment', [$comment])) {
            throw new Exception('You are not authorized to delete this comment', 500);
        }
        $this->commentRepository->delete($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return $this->commentRepository->show($id);
    }
}
