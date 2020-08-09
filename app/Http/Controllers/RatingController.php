<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Tymon\JWTAuth\Facades\JWTAuth;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $bookId = $request->bookId;
        $ratings = Rating::where('book_id', $bookId)->get();
        $this->sendResponse($ratings, null);
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
        $response = Gate::allows('add-rating', [$bookCreator]);
        if (!$response) {
            $this->sendError('You are not authorized to add rating', null, 500);
        }
        // validate form data
        $request->validate([
            'value' => 'required'
        ]);
        $rating = new Rating();
        $rating->book_id = $request->book_id;
        $rating->value = $request->value;
        $rating->created_by = JWTAuth::user()->username;
        $rating->save();
        return $rating;
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
        // authorize
        $response = Gate::allows('update-rating', [$rating]);
        if (!$response) {
            $this->sendError('You are not authorized to update this rating', null, 500);
        }

        $rating->value = $request->value;
        $rating->updated_by = JWTAuth::user()->username;
        $rating->save();
        return $rating;
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
        // authorize
        $response = Gate::allows('delete-rating', [$rating]);
        if (!$response) {
            $this->sendError('You are not authorized to delete this rating', null, 500);
        }
        $rating->delete();
        $this->sendResponse($rating, "Rating deleted");
    }
}
