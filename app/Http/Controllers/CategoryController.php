<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function findCategories($name){
        $initial_categories = [];
        $categories = $this->searchCategoryAndGetArticles($initial_categories, $name);
        return $categories->toArray();
    }

    private function searchCategoryAndGetArticles($arrayCategories, $id)
    {
        // find category by coming name includ articles
        $category = Category::where("id", "=", $id)->with("articles")->first();

        if (!$category) {
            return null; // Categorie not find
        }

        // Récupération des articles de cette catégorie
        $articles = $category->articles;

        // find subcategories of  category
        $childCategories = $category->childCategories;

        // Parcours récursif des sous-catégories
        foreach ($childCategories as $childCategory) {
            $subCategoryArticles = $this->searchCategoryAndGetArticles([], $childCategory->nom);
            if ($subCategoryArticles) {
                $articles = $articles->merge($subCategoryArticles);
            }
        }

        return $articles;
    }






    // guestion 9

    public function getArticlesByCategory($name)
    {
        $category = $this->searchCategoryAndGetArticles($name);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Fetch articles for the current category
        $articles = $category->articles()->where('expiration_date', '>', now())->get();

        // Fetch articles from subcategories recursively
        foreach ($category->childCategories as $childCategory) {
            $subCategoryArticles = $this->getArticlesRecursively($childCategory);
            $articles = $articles->merge($subCategoryArticles);
        }

        return response()->json($articles);
    }

    private function searchCategoryAndGetArticlesnoexpired($name)
    {
        return Category::where("nom", "=", $name)->with("articles")->first();
    }

    private function getArticlesRecursively($category)
    {
        $articles = $category->articles()->where('expiration_date', '>', now())->get();

        foreach ($category->childCategories as $childCategory) {
            $subCategoryArticles = $this->getArticlesRecursively($childCategory);
            $articles = $articles->merge($subCategoryArticles);
        }

        return $articles;
    }
}
