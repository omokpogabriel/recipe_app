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

    /**
     * creates a one to one relation with user model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * creates a one to one relationship with admin_comment model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function comment(){
        return $this->hasOne(AdminComment::class);
    }
}
