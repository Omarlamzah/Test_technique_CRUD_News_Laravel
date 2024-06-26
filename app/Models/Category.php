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
    public function  childCategories (){
        return $this->hasMany(Category::class,"parent_id",);
    }
    public function  news (){
        return $this->hasMany(News::class,"Categorie_id",);
    }


   // public function  articles(){return $this->hasMany(Article::class,"category_id",);}

}
