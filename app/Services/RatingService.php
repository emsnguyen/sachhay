<?php namespace App\Services;
use App\Http\Requests\CreateRatingRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Requests\UpdateRatingRequest;
use App\Models\Book;
use App\Models\Rating;
use App\Repositories\BookRepository;
use App\Repositories\CommentRepository;
use App\Repositories\RatingRepository;
use Illuminate\Support\Facades\Gate;
use Tymon\JWTAuth\Facades\JWTAuth;

class RatingService
{
    protected $ratingRepository;
    public function __construct(RatingRepository $ratingRepository,
                                BookRepository $bookRepository)
    {
        $this->ratingRepository = $ratingRepository;
        $this->bookRepository = $bookRepository;
    }

    /**
     * @param CreateRatingRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function create(CreateRatingRequest $request) {
        $bookCreator = $this->bookRepository->show($request->input('book_id'))->created_by;
        $response = Gate::allows('add-rating', [$bookCreator]);
        if (!$response) {
            throw new \Exception('You are not authorized to add rating', 500);
        }

        return $this->ratingRepository->create([$request]);
    }

    /**
     * @param UpdateRatingRequest $request
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function update(UpdateRatingRequest $request, $id) {
        $rating = $this->show($id);
        $response = Gate::allows('update-rating', [$rating]);
        if (!$response) {
            throw new \Exception('You are not authorized to update this rating', 500);
        }
        return $this->ratingRepository->update([$request], $id);
    }

    /**
     * @param int $id
     * @throws \Exception
     */
    public function delete(int $id)
    {
        $rating = $this->show($id);
        // authorize
        $response = Gate::allows('delete-rating', [$rating]);
        if (!$response) {
            throw new \Exception('You are not authorized to delete this rating', 500);
        }
        $this->ratingRepository->delete($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return $this->ratingRepository->show($id);
    }

    public function all()
    {
        return $this->ratingRepository->all();
    }
}
