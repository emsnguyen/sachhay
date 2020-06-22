<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', "BookController@index");
Route::group(['prefix'=>'dashboard'], function(){
    Route::view('/','dashboard/dashboard');
    // book routes
    Route::get('books/create/{id}', 'BookController@create');
    Route::resource('books', 'BookController')->except('create');
    Route::get('books/search', 'BookController@search');
    // comment routes
    Route::post('comments', 'CommentController@store');
    // Route::resource('comments', 'CommentController')->except('store');
    // rating routess
    Route::post('ratings', 'RatingController@store');
    // Route::resource('ratings', 'RatingController')->except('store');
    // user routes
    Route::get('users/create/{id}', 'UserController@create');
    Route::resource('users', 'UserController')->except('create');
    Route::get('users/search', 'UserController@search');
    
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
