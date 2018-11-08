<?php

use Illuminate\Http\Request;

use App\Category;
use App\Note;



Route::get('/notes/', 'NoteController@index');
Route::get('/notes/{id}', function($id) {
	return Note::find($id);
});


Route::patch('/notes/{id}', function(Request $request, $id) {
	Note::findOrFail($id)->update(['type' => $request->input(['type']),
		'category_id'  => $request->input(['category_id']),
		'amount' => $request->input(['amount']),
		'created_at' => $request->input(['created_at']),
		'comment' => $request->input(['comment'])
	]);
});

Route::post('/notes/store', function(Request $request) {
	return Note::create(['type' => $request->input(['type']),
		'category_id'  => $request->input(['category_id']),
		'amount' => $request->input(['amount']),
		'created_at' => $request->input(['created_at']),
		'comment' => $request->input(['comment'])
	]);
});

Route::get('/categories/', 'CategoryController@index');












Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
