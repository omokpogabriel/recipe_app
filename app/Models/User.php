<?php

namespace App\Models;

use App\Events\NewUserEvent;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verification_token',
        'isActive',
        'roles',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * dispatches events when a new user is created
     *
     * @var string[]
     */
    protected $dispatchesEvents=[
        'created' => NewUserEvent::class
    ];

    /**
     * creates a one to one relationship with Profile modes
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile(){
        return $this->hasOne(Profile::class, 'user_id','id');
    }

    /**
     * creates a one to many relationship with recipe model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recipes(){
        return $this->hasMany(Recipe::class);
    }
}
