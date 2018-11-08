<?php

use Illuminate\Http\Request;

use App\Category;
use App\Note;



// Route::get('/', function() {
// 	return Note::latest()->orderBy('created_at', 'desc')->get();
// });
Route::get('/notes/', 'NoteController@index');
Route::get('/notes/{id}', function($id) {
	return Note::find($id);
});

Route::get('/categories/', 'CategoryController@index');












Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
