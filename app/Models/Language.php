<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'image',
        'shortdescription',
        'description',
        'explainlanguage',
        'languagemother_id',
        'order_language'

    ];

    public function userlanguagebuys()
    {
        return $this->hasMany(Userlanguagebuy::class);
    }

    public function user(){

        return $this->belongsTo(User::class);

    }


    public function periods(){

        return $this->hasMany(Period::class);

    }

    public function sliderlanguage(){

        return $this->hasMany(Sliderlanguage::class);

    }


    public function languagemother(){
        return $this->belongsTo(Languagemother::class) ;
    }



    public function parts(){
        return $this->hasMany(Part::class) ;
    }



}
