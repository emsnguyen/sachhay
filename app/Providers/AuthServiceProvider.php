<?php

namespace App\Providers;

use App\Models\Book;
use App\Models\Comment;
use App\Models\Rating;
use App\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('add-book', function (User $user) {
            // user can add book only if he is not banned
            $isBanned = $user->banned;
            return !$isBanned;
        });
        Gate::define('update-book', function ($user, $book) {
            // user can update/ delete book only if he is not banned and (he is admin or book creator)
            $isBanned = $user->banned;
            $isAdmin = $user->role === 1;
            $isBookCreator = $book->created_by === $user->name;
            return !$isBanned && ($isAdmin || $isBookCreator);
        });
        Gate::define('delete-book', function ($user, $book) {
            // user can update/ delete book only if he is not banned and (he is admin or book creator)
            $isBanned = $user->banned;
            $isAdmin = $user->role === 1;
            $isBookCreator = $book->created_by === $user->name;
            return !$isBanned && ($isAdmin || $isBookCreator);
        });
        // Gate::define('update-comment', 'delete-comment', function ($user, Comment $comment) {
        //     // user can update comment only if he is not banned and (he is admin or comment creator)
        //     $isBanned = $user->banned;
        //     $isAdmin = $user->role === 1;
        //     $isCommentCreator = $comment->created_by === $user->name;
        //     return !$isBanned && ($isAdmin || $isCommentCreator);
        // });
        // Gate::define('add-rating', 'add-comment', function (User $user, $bookCreator) {
        //     // user can add rating/comment only if he is not banned and he is admin and he is not book creator 
        //     $isBanned = $user->banned;
        //     $isAdmin = $user->role === 1;
        //     $isBookCreator = $bookCreator === $user->name;
        //     return $isAdmin || (!$isBanned && !$isBookCreator);
        // });
        // Gate::define('update-rating', 'delete-rating', function (User $user, Rating $rating) {
        //     // user can update rating only if he is not banned and (he is admin or comment creator)
        //     $isBanned = $user->banned;
        //     $isAdmin = $user->role === 1;
        //     $isRatingCreator = $rating->created_by === $user->name;
        //     return !$isBanned && ($isAdmin || $isRatingCreator);
        // });
    }
}