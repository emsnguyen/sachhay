<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRatingRequest;
use App\Http\Requests\UpdateRatingRequest;
use App\Services\RatingService;
use Illuminate\Http\Response;

class RatingController extends Controller
{
    protected $ratingService;
    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateRatingRequest $request
     * @return void
     * @throws \Exception
     */
    public function store(CreateRatingRequest $request)
    {
        $rating = $this->ratingService->create($request);
        return $this->sendResponse($rating, "Rating updated");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRatingRequest $request
     * @param int $id
     * @return void
     * @throws \Exception
     */
    public function update(UpdateRatingRequest $request, $id)
    {
        $rating = $this->ratingService->update($request, $id);
        return $this->sendResponse($rating, "Rating updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->ratingService->delete($id);
        return $this->sendResponse($id, "Rating deleted");
    }
}
