<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $fillable =["nom","category_id"];

    public function  parentCategory (){
        return $this->belongsTo(Category::class,"category_id");
    }

}
