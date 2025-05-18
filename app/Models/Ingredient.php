<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'ingredient_product');
    }
    public function allergies()
    {
        return $this->belongsToMany(Allergy::class, 'allergy_ingredient');
    }
}
