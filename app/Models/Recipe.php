<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
                'recipe_name',
                'title',
                'description',
                'recipe_picture',
                'ingredients',
                'nutritional_value',
                'cost',
                'primary_ingredients',
                'main_ingredients',
                'meal'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comment(){
        return $this->hasOne(AdminComment::class);
    }
}
