<?php

use App\Http\Controllers\ListoverviewController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\NoteLabelController;
use App\Http\Controllers\TodoController;
use App\Models\Listoverview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Notes
Route::get('/notes', [NoteController::class,'index']);
Route::get('/notes/search/{searchTerm}', [NoteController::class,'findBySearchTerm']);
Route::get('/notes/{note_id}', [NoteController::class,'findNoteByID']);

// Labels
Route::get('/labels', [LabelController::class,'index']);
Route::get('/labels/checkname/{name}', [LabelController::class, 'checkName']);
Route::get('/labels/search/{searchTerm}', [LabelController::class,'findBySearchTerm']);
Route::get('/labels/{label_id}', [LabelController::class,'findLabelByID']);

// Listoverviews
Route::get('/listoverviews', [ListoverviewController::class,'index']);
Route::get('/listoverviews/{listoverview_id}', [ListoverviewController::class,'findListByID']);

// Todos
Route::get('/todos', [TodoController::class,'index']);
Route::get('/todos/search/{searchTerm}', [TodoController::class,'findBySearchTerm']);
Route::get('/todos/{todo_id}', [TodoController::class,'findTodoByID']);

// Login
Route::post('/auth/login', [\App\Http\Controllers\AuthController::class, 'login']);

// Speichern, Updaten und Löschen werden nun abgesichtert, sodass sie nur funktionieren, wenn man angemeldet ist
Route::group(['middleware' => ['api','auth.jwt','auth.admin']], function(){
    // Notes -----------------------------------------------------------------------------------------------------------
    Route::post('/notes', [NoteController::class, 'save']);
    Route::put('/notes/{note_id}', [NoteController::class, 'update']);
    Route::delete('/notes/{note_id}', [NoteController::class, 'delete']);

    // Labels ----------------------------------------------------------------------------------------------------------
    Route::post('/labels', [LabelController::class, 'save']);
    Route::put('/labels/{label_id}', [LabelController::class, 'update']);
    Route::delete('/labels/{label_id}', [LabelController::class, 'delete']);

    // Listoverviews ---------------------------------------------------------------------------------------------------
    Route::post('/listoverviews', [ListoverviewController::class, 'save']);
    Route::put('/listoverviews/{listoverview_id}', [ListoverviewController::class, 'update']);
    Route::delete('/listoverviews/{listoverview_id}', [ListoverviewController::class, 'delete']);

    // Todos -----------------------------------------------------------------------------------------------------------
    Route::post('/todos', [TodoController::class, 'save']);
    Route::put('/todos/{todo_id}', [TodoController::class, 'update']);
    Route::delete('/todos/{todo_id}', [TodoController::class, 'delete']);
});

