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

Route::post('auth/register', 'APIController@register')->name('register');
Route::post('auth/login', 'APIController@login')->name('login');
Route::post('auth/logout', 'APIController@logout')->name('logout');
// book routes
Route::get('books/search', 'BookController@search')->name('books.search');
Route::resource('books', 'BookController');

// comment routes
Route::post('comments', 'CommentController@store');
Route::resource('comments', 'CommentController')->except('store');
// rating routess
Route::post('ratings', 'RatingController@store');
Route::resource('ratings', 'RatingController')->except('store');
// user routes
Route::get('users/create/{id}', 'UserController@create');
Route::resource('users', 'UserController');
Route::get('users/search', 'UserController@search');

