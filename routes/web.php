<?php

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

Route::get('/', 'MainController@index');

Route::get('/post/{slug}', 'MainController@show')->name('post.show');
Route::get('/tag/{slug}', 'MainController@tag')->name('tag.show');
Route::get('/category/{slug}', 'MainController@category')->name('category.show');

Route::post('favorite/{post}', 'MainController@favoritePost');
Route::post('unfavorite/{post}', 'MainController@unFavoritePost');


Route::group(['prefix'=>'admin', 'namespace'=>'Admin','middleware'	=>	'admin'], function (){
    Route::get('/', 'DashboardController@index');
    Route::resource('/categories','CategoriesController');
    Route::resource('/tags','TagsController');
    Route::resource('/users','UsersController');
    Route::resource('/posts', 'PostsController');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::group(['middleware'	=>	'auth'], function(){
    Route::get('/profile', 'ProfileController@index');
    Route::post('/profile', 'ProfileController@store');
    Route::get('/my_favorites', 'ProfileController@myFavorites');
});