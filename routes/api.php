<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([
    'prefix' => 'auth'

], function () {
    Route::post('register', 'APIController@register')->name('register');
    Route::post('login', 'APIController@login')->name('login');
    Route::post('logout', 'APIController@logout')->name('logout');
    Route::post('refresh', 'APIController@refresh')->name('refresh');
});
Route::middleware('jwt.auth')->group( function(){
    // book routes
    Route::get('books/search', 'BookController@search')->name('books.search');
    Route::resource('books', 'BookController');
    // comment routes
    Route::resource('comments', 'CommentController');
    // rating routes
    Route::resource('ratings', 'RatingController');
    // user routes
    Route::get('users/create/{id}', 'UserController@create');
    Route::resource('users', 'UserController');
    Route::get('users/search', 'UserController@search');
} );
