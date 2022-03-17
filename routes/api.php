<?php

use App\Http\Controllers\Api\publ\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\publ\AuthorController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::resource('authors', AuthorController::class);
Route::post('/authors/{author}/photo', [AuthorController::class, 'uploadPhoto']);
Route::resource('books', BookController::class);
Route::post('/books/{book}/photo', [BookController::class, 'uploadPhoto']);

