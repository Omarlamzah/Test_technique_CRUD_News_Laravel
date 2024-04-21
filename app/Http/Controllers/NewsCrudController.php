<?php

namespace App\Http\Controllers;

use App\Models\News;
use http\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NewsCrudController extends Controller
{
    public function getNews(){
        try {
            $currentDate = now();
            $news = News::where("Date_expiration", ">", $currentDate)->orderBy('Date_debut', 'desc')->get();

            if($news->count() > 0){
                return response()->json(["news" => $news], 200);
            } else {
                return response()->json(["news" => null, "message" => "Not found any news"], 400);
            }
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage(), "message" => "An error occurred while fetching news."], 500);
        }
    }

    public function createNews(Request $request){
        // Validate the incoming request data
        $validator = $this->validateNewsField($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()], 422);
        }

        try {
            // If validation passes, create news using Eloquent
            $news = News::create($request->all());
            return response()->json(["news" => $news, "message" => "News created successfully"], 201);
        } catch (\Exception $e) {
            // Return error response if an exception occurs
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }





    public function updateNews(Request $request, $id)
    {
        // search news by id
        $news = News::find($id);
        // check if the news item exists
        if (!$news) {
            return response()->json(['message' => 'News not found'], 400);
        }
        // Update the news item attributes based on the request data
        if ($request->has('Titre')) {    $news->Titre = $request->input('Titre');   }
        if ($request->has('Contenu')) {      $news->Contenu = $request->input('Contenu');  }
        if ($request->has('Categorie_id')) {  $news->Categorie_id = $request->input('Categorie_id'); }
        if ($request->has('Date_debut')) {   $news->Date_debut = $request->input('Date_debut');  }
        if ($request->has('Date_expiration')) {  $news->Date_expiration = $request->input('Date_expiration');  }
        // Save the changes
        $news->save();
        return response()->json(["news" => $news, "message" => "News updated successfully"], 201);
    }

    public function deleteNews($id)
    {
        try {
            // Find the news item by its ID
            $news = News::find($id);
            // Delete the news item
            if (!$news) {
                return response()->json(['message' => 'News not found'], 400);
            }
            $news->delete();
            // Return a success message with 204 but 204 what i see in test its not good to send this code 204 its mean no content but user should know status her request
            return response()->json(['message' => 'News deleted successfully'], 204);
        } catch (\Exception $e) {
            // Handle any unexpected exceptions
            return response()->json(['message' => 'Failed to delete news item'], 500);
        }
    }





    private function  validateNewsField($request)
    {
        // Validate the incoming request data
        $validatedData =  Validator::make($request->all(), [
            'Titre' => 'required|string|max:255',
            'Contenu' => 'required|string',
            'Categorie_id' => 'required|integer',
            'Date_debut' => 'required|date',
            'Date_expiration' => 'required|date|after_or_equal:Date_debut',
        ]);

        return $validatedData;
    }


}
