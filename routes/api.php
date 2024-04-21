<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NewsCrudController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get("/getnews", [NewsCrudController::class, "getNews"]);
    Route::post("/createnews", [NewsCrudController::class, "createNews"]);
    Route::put("/updatenews/{id}", [NewsCrudController::class, "updateNews"]);
    Route::delete("/deletenews/{id}", [NewsCrudController::class, "deleteNews"]);
});

//question 8
Route::get("/findarticles/{id}", [CategoryController::class, "findcategories"]);
//question 9
Route::get("/findcategories/{nom}",
function ( $nom){
    $articles =[];
    $category = Category::where("nom", "=", $nom)
         ->with(['articles', 'news' => function ($query) {
            $query->where("Date_expiration", ">", now()); }])
        ->first();
                    // this  articles function from modale
    $articles[] = $category->articles;
                    // this  childCategories function from modale
    $subcategory= $category->childCategories;

    if($subcategory){
        return;
    }

    foreach ($subcategory as $category) {

    }
}


);

