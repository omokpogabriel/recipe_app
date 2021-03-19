<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminComment extends Model
{
    use HasFactory;

    protected $fillable =['admin_comment'];


    public function recipe(){
        return $this->belongsTo(Recipe::class);
    }
}
