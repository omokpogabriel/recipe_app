<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminComment extends Model
{
    use HasFactory;

    protected $fillable =['admin_comment'];


    /**
     * creates a one to many relation with recipe model
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipe(){
        return $this->belongsTo(Recipe::class);
    }
}
