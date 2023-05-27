<?php

use App\Models\BookGenres;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/usuarios', function(){
    return view('user');
});

Route::get('/livros', function(){

    // busca generos

    $genres = BookGenres::all();



    return view('books' , ['genres' => $genres]);
});
