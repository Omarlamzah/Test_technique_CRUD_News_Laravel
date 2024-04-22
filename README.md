<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Answer test 


1. Créer une nouvelle application Laravel 10.
    answer ==>  composer create-project laravel/laravel:^10.0 CRUD_de_News_en_Laravel

2.  Configurez un modèle de données pour les news avec les champs 
   suivants : • Titre• Contenu • Categorie • Date_debut • Date_expiration
  
2. answer ===>  cmd : php artisan make:model News
         ===> model code : php artisan make:model News
                            <?php

                            namespace App\Models;

                            use Illuminate\Database\Eloquent\Factories\HasFactory;
                            use Illuminate\Database\Eloquent\Model;

                            class News extends Model
                            {
                                use HasFactory;
                                protected $fillable = [
                                    'Titre',
                                    'Contenu',
                                    'Categorie_id',
                                    'Date_debut',
                                    'Date_expiration',
                                ];

                                public function categories(){
                                    return $this->belongsTo(Category::class,"Categorie_id");
                                }


                            }

3.  1   Créer un modèle de données pour une structure arborescente de 
    catégories de news. Chaque catégorie doit avoir un nom et une relation 
    parent/enfant pour former un arbre de catégories. (il n’est pas demandé 
    de faire de la CRUD sur les catégories mais de concevoir un modèle de donnée 
    et remplir la table correspondante avec l’arbre de catégories que vous 
    trouverez annexé à cet exercice)

3. answer ===>  cmd : php artisan make:model Category
         ===> model code : php artisan make:model Category

                                    <?php
                            namespace App\Models;
                            use Illuminate\Database\Eloquent\Factories\HasFactory;
                            use Illuminate\Database\Eloquent\Model;
                            class Category extends Model
                            {
                                use HasFactory;
                                protected $fillable = [ 'nom',  'parent_id',  ];
                                public function  parentCategory (){
                                    return $this->belongsTo(Category::class,"parent_id");
                                }
                                public function  news (){
                                    return $this->hasMany(News::class,"Categorie_id",);
                                }}

 3.  2   =====> Question: remplir la table correspondante avec l’arbre de catégories que vous trouverez annexé à cet exercice
 
 3.  2  answer ===>  cmd : php artisan make:factory CategoryFactory
        ===>  CategoryFactory code :
                                class CategoryFactory extends Factory
                                {
                                    protected $model = Category::class;

                                    public function definition()
                                    {
                                        return [
                                            'nom' => $this->faker->unique()->word,
                                            'parent_id' => null,
                                        ];
                                    }

                                    public function configure()
                                    {
                                        return $this->afterCreating(function (Category $category) {
                                            $childrenData = [
                                                'Actualités' => ['Politique', 'Économie', 'Sport'],
                                                'Divertissement' => ['Cinéma', 'Musique', 'Sorties'],
                                                'Technologie' => [
                                                    'Informatique' => ['Ordinateurs de bureau', 'PC portable', 'Connexion internet'],
                                                    'Gadgets' => ['Smartphones', 'Tablettes', 'Jeux vidéo'],
                                                ],
                                                'Santé' => ['Médecine', 'Bien-être'],
                                            ];

                                            if (isset($childrenData[$category->nom])) {
                                                foreach ($childrenData[$category->nom] as $childName => $subCategories) {
                                                    // Check if the current childName is an array
                                                    if (is_array($subCategories)) {

                                                        $childCategory = Category::factory()->create([
                                                            'nom' => $childName,
                                                            'parent_id' => $category->id,
                                                        ]);

                                                        // Create subcategories
                                                        foreach ($subCategories as $subChildName) {
                                                            Category::factory()->create([
                                                                'nom' => $subChildName,
                                                                'parent_id' => $childCategory->id,
                                                            ]);
                                                        }
                                                    }
                                                    else {
                                                        // category it's not an array
                                                        Category::factory()->create([
                                                            'nom' => $subCategories,
                                                            'parent_id' => $category->id,
                                                        ]);
                                                    }
                                                }
                                            }
                                        });
                                    }
                                }
 3.  2  the end of answer  3.  2 :  php artisan db:seed --class=CategorySeeder


4. Créer un contrôleur pour gérer les opérations CRUD sur les news (création, lecture, mise à jour, suppression).

4 answer ====> cmd : php artisan make:controller NewsCrudController 
               routers :
                        
         
                    Route::middleware(['auth:sanctum'])->group(function () {
                        Route::get("/getnews", [NewsCrudController::class, "getNews"]);
                        Route::post("/createnews", [NewsCrudController::class, "createNews"]);
                        Route::put("/updatenews/{id}", [NewsCrudController::class, "updateNews"]);
                        Route::delete("/deletenews/{id}", [NewsCrudController::class, "deleteNews"]);
                    });


                 NewsCrudController  code class NewsCrudController extends Controller
                        {
                            public function getNews(){
                                try {
                                    $currentDate = now();
                                    $news = News::where("Date_expiration", ">", $currentDate)->orderBy('id', 'desc')->get();

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



5. et 6.  Créer un middleware pour restreindre l'accès à l'API aux utilisateurs  authentifiés. Appliquez ce middleware aux routes de l'API.
       answer NewsCrudController xode+
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::get("/getnews", [NewsCrudController::class, "getNews"]);
            Route::post("/createnews", [NewsCrudController::class, "createNews"]);
            Route::put("/updatenews/{id}", [NewsCrudController::class, "updateNews"]);
            Route::delete("/deletenews/{id}", [NewsCrudController::class, "deleteNews"]);
        });   

7. Pour la liste des news, Créer une route distincte qui affiche les news dans l'ordre décroissant de leur date de publication (ne pas afficher les news expirées).
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


8. Mettez en œuvre un algorithme de recherche récursif qui parcourt l'arborescence des catégories pour trouver la catégorie demandée et  récupérer tous les articles associés à cette catégorie et à ses souscatégories.

                  public  function searchArticlesRecursive($categoryId, &$articlesArray = []) {
                        // search main category with its subcategories and articles
                        $parentCategory = Category::where("id", "=", $categoryId)->with(["childCategories", "articles"])->first();

                        // append to $articlesArray articles of main category
                        $articlesArray = array_merge($articlesArray, $parentCategory->articles->toArray());

                        // loop through childCategories
                        foreach ($parentCategory->childCategories as $category) {
                            // call _searchArticlesRecursive again (it's a private method of the same class)
                            $this->searchArticlesRecursive($category->id, $articlesArray);
                        }

                        // return the combined articles array
                        return $articlesArray;
                    }



  9. Développez une nouvelle route dans votre API pour rechercher une catégorie spécifique dans l'arborescence, en fonction de son nom, et renvoyer tous les articles associés à cette catégorie, y compris les articles des sous-catégories (n’afficher dans les résultats que les news non expirées)
       answer ===> route Route::get('/findcategories/{nom}',[ArticlesController::class,"searchCategory"]);
        function  searchCategory  ===>
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



10. Utilisez des outils comme Postman pour tester les fonctionnalités de votre API en effectuant des requêtes POST, PUT, GET et DELETE pour ajouter, mettre à jour, récupérer et supprimer des news.

  ===> import this link in your Postman 
          " i am remove it because repo is public "


  11. Assurez-vous que l'API répond aux codes d'état HTTP appropriés pour chaque opération (par exemple, 200 pour OK, 201 pour la création, 204 pour la suppression, 400 pour les erreurs de demande, etc.)
    ===> those status code  used in the code