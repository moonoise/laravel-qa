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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('questions','QuestionsController')->except('show');

// Route::post('/questions/{question}/answers', 'AnswersController@store')->name('answers.store');

Route::resource('questions.answers','AnswersController')->except(['index','create','show']);

Route::get('/questions/{slug}' , 'QuestionsController@show')->name('questions.show');  // name() เปลี่ยนจาก /questions  เป็น question.show

Route::post('/answers/{answer}/accept','AcceptAnswerController')->name('answers.accept');  // ต้องสร้าง AcceptAnswerController ### php artisan make:controller AcceptAnswerController ## ก่อนทำการเขียน route 

Route::post('/questions/{question}/favorites','FavoritesController@store')->name('questions.favorite');

Route::delete('/questions/{question}/favorites','FavoritesController@destroy')->name('questions.unfavorite');