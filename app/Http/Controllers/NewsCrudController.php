<?php

namespace App\Http\Controllers;

use App\Models\News;
use http\Message;
use Illuminate\Http\Request;

class NewsCrudController extends Controller
{
    public function getNews(){
        try {
            $currentDate = now();
            $news = News::where("Date_expiration", ">", $currentDate)->orderBy('id', 'desc')->get();

            if($news->count() > 0){
                return response()->json(["news" => $news], 200);
            } else {
                return response()->json(["news" => null, "message" => "Not found any news"], 200);
            }
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage(), "message" => "An error occurred while fetching news."], 500);
        }
    }





}
