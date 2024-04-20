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
