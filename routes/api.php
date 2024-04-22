<?php

use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\NewsCrudController;
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


require __DIR__.'/auth.php';

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get("/getnews", [NewsCrudController::class, "getNews"]);
    Route::post("/createnews", [NewsCrudController::class, "createNews"]);
    Route::put("/updatenews/{id}", [NewsCrudController::class, "updateNews"]);
    Route::delete("/deletenews/{id}", [NewsCrudController::class, "deleteNews"]);
});
Route::get('/findcategories/{nom}',[ArticlesController::class,"searchCategory"]);



