<?php

use Illuminate\Http\Request;

use App\Category;
use App\Note;
use App\Type;


Route::get('/notes/', 'NoteController@index');
Route::get('/notes/{id}', function($id) {
	return Note::find($id);
});
Route::get('/types/', 'TypeController@index' );
Route::get('/categories/', 'CategoryController@index');

Route::patch('/notes/{id}', function(Request $request, $id) {
	Note::findOrFail($id)->update(['type_id' => $request->input(['type_id']),
		'category_id'  => $request->input(['category_id']),
		'amount' => $request->input(['amount']),
		// 'created_at' => $request->input(['created_at']),
		'note_date' => $request->input(['note_date']),
		'comment' => $request->input(['comment'])
	]);
});

Route::post('/notes/store', function(Request $request) {
	return Note::create(['type_id' => $request->input(['type_id']),
		'category_id'  => $request->input(['category_id']),
		'amount' => $request->input(['amount']),
		'note_date' => $request->input(['note_date']),
		// 'created_at' => $request->input(['created_at']),
		'comment' => $request->input(['comment'])
	]);
});

Route::delete('/notes/{id}', function($id) {
	return Note::destroy($id);
});

Route::get('/categories/', 'CategoryController@index');












Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
