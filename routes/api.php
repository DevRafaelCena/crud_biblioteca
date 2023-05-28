<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/user-library/filter/{term}', [App\Http\Controllers\UsersLibraryController::class, 'filter']);
Route::resource('/user-library', App\Http\Controllers\UsersLibraryController::class);

Route::resource('/books', App\Http\Controllers\BooksController::class);

Route::resource('/loans', App\Http\Controllers\LoansController::class);
