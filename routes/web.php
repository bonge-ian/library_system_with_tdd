<?php

use Illuminate\Support\Facades\Route;

Route::post('/book', 'BooksController@store');
Route::patch('/book/{book}', 'BooksController@update');
Route::delete('/book/{book}', 'BooksController@destroy');

// author routes
Route::post('/author', 'AuthorsController@store');