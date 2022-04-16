<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Languagemother extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'shortdescription',
        'description'
        // 'order_language'
    ];

    public function languages(){
        return $this->hasMany(Language::class) ;
    }

}
