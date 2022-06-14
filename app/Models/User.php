<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'family',
        'username',
        'phone',
        // 'email',
        'password',
        'languagemother_id'
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }


    public function languagemother()
    {
        return $this->belongsTo(Languagemother::class);
    }

    public function sliders()
    {
        return $this->hasMany(Slider::class);
    }

    public function performances()
    {
        return $this->hasMany(Performance::class);
    }

    public function slideranswers()
    {
        return $this->hasManyThrough(Slideranswer::class , Slider::class);
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class);
    }

    public function hasLanguage($language)
    {
        return $this->languages->contains('id' , $language->id) ;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
