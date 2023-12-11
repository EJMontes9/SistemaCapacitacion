<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    //Relacion de uno a uno
    public function profile(){
        return $this->hasOne('App/Models/profile');
    }

    //Relacion de uno a muchos
    public function courses_dictated(){
        return $this->hasMany('App/Models/courses');
    }

    //Relacion de muchos a muchos
    public function courses_enrolled(){
        return $this->belongsToMany('App/Models/courses');
    }

    public function reviews(){
        return $this->hasMany('App/Models/review');
    }

    public function comments(){
        return $this->hasMany('App/Models/comment');
    }

    public function reactions(){
        return $this->hasMany('App/Models/reaction');
    }

    public function lessons(){
        return $this->belongsToMany('App/Models/lesson');
    }

}
