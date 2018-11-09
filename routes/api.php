<?php

use Illuminate\Http\Request;

use App\Category;
use App\Note;



Route::get('/notes/', 'NoteController@index');
Route::get('/notes/{id}', function($id) {
	return Note::find($id);
});
Route::get('/types/', 'TypeController@index' );

Route::patch('/notes/{id}', function(Request $request, $id) {
	Note::findOrFail($id)->update(['type_id' => $request->input(['type_id']),
		'category_id'  => $request->input(['category_id']),
		'amount' => $request->input(['amount']),
		'created_at' => $request->input(['created_at']),
		'comment' => $request->input(['comment'])
	]);
});

Route::post('/notes/store', function(Request $request) {
	return Note::create(['type_id' => $request->input(['type_id']),
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
