<?php

use Illuminate\Support\Facades\Route;

Route::post('/book', 'BooksController@store');
Route::patch('/book/{book}', 'BooksController@update');
