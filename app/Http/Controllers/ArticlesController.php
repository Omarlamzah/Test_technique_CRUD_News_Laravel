<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{

//  gestion 8 get  recursive

    public function getArticlesRecursive(){
        $articlesArray= $this->searchArticlesRecursive(1);
        return response()->json($articlesArray);
    }
    public  function searchArticlesRecursive($categoryId, &$articlesArray = []) {
        // search main category with its subcategories and articles
        $parentCategory = Category::where("id", "=", $categoryId)->with(["childCategories", "news"])->first();

        // append to $articlesArray articles of main category
        $articlesArray = array_merge($articlesArray, $parentCategory->news->toArray());

        // loop through childCategories
        foreach ($parentCategory->childCategories as $category) {
            // call _searchArticlesRecursive again (it's a private method of the same class)
            $this->searchArticlesRecursive($category->id, $articlesArray);
        }

        // return the combined articles array
        return $articlesArray;
    }



    /// question 9
    public function searchCategory($nom)
    {
        // Rechercher la catégorie par son nom
        $category = Category::where('nom', $nom)->first();

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        // Récupérer tous les articles associés à cette catégorie et à ses sous-catégories
        $articlesArray = $this->searchArticlesRecursive($category->id);
      //  return $articlesArray;
        // Filtrer les articles pour ne conserver que ceux qui ne sont pas expirés
        $validArticles = [];

        foreach ($articlesArray as $article) {
            // Vérifier la date d'expiration de l'article
            if ($article['Date_expiration'] > now()) {
                // Ajouter l'article au tableau des articles valides
                $validArticles[] = $article;
            }
        }

        // Retourner les articles valides en tant que réponse
        return response()->json(['articles' => $validArticles], 200);
    }






}
