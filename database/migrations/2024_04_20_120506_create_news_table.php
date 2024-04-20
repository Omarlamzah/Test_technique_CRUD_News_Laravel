<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string("Titre");                               //• Titre
            $table->string("Contenu",500);                             //• Contenu
            $table->unsignedBigInteger("Categorie_id");           //• Categorie
            $table->date("Date_debut");                           //• Date_debut
            $table->date("Date_expiration");                     //• Date_expiration
            $table->timestamps();                                       //• updated and create date
                                                                       // relationship between categories table  //
            $table->foreign("Categorie_id")->references("id")->on("categories")
                ->onDelete('cascade')
                ->onUpdate('cascade');



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
