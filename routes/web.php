<?php

use App\Models\News;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $currentDate = now();
    $news = News::where("Date_expiration", ">", $currentDate)->orderBy('id', 'desc')->get();
    return $news;
    //return view('welcome');
});
