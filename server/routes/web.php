<?php

use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Models\Note;


Route::get('/', [NoteController::class,'index']);
Route::get('/notes', [NoteController::class,'index']);
Route::get('/notes/{note}',[NoteController::class,'show']);




/*

// Notes -----------------------------------------------------------------------------------
Route::get('/notes', function () {
    $notes = DB::table('notes')->get();
    return view('notes.index',compact('notes'));
});
Route::get('/notes/{id}', function ($id) {
    $note = DB::table('notes')->find($id);
    return view('notes.show',compact('note'));
});

Route::get('/notes/{id}', function ($id) {
    $note = Note::find($id);
    return view('notes.show',compact('note'));
});

// List Overview ----------------------------------------------------------------------------
Route::get('/listoverview', function () {
    $listoverview = DB::table('listoverview')->get();
    return view('listoverview.index',compact('listoverview'));
});

Route::get('/listoverview/{id}', function ($id) {
    $listview = DB::table('listoverview')->find($id);
    return view('listoverview.show',compact('listview'));
});

// Labels ----------------------------------------------------------------------------
Route::get('/labels', function () {
    $labels = DB::table('labels')->get();
    return view('labels.index',compact('labels'));
});
Route::get('/labels/{id}', function ($id) {
    $label = DB::table('labels')->find($id);
    return view('labels.show',compact('label'));
});

// Todos ----------------------------------------------------------------------------
Route::get('/todos', function () {
    $todos = DB::table('todos')->get();
    return view('todos.index',compact('todos'));
});
Route::get('/todos/{id}', function ($id) {
    $to do = DB::table('todos')->find($id);
    return view('todos.show',compact('to do'));
});

// Images ----------------------------------------------------------------------------
Route::get('/images', function () {
    $images = DB::table('images')->get();
    return view('images.index',compact('images'));
});
Route::get('/images/{id}', function ($id) {
    $image = DB::table('images')->find($id);
    return view('images.show',compact('image'));
});

*/
