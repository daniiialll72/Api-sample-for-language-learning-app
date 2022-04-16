<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'title',
        'description',
        'language_id',
        'order_period',
    ];

    public function language(){
        return $this->belongsTo(Language::class) ;
    }

    public function levels(){
        return $this->hasMany(Level::class) ;
    }

    public function lessons(){
        return $this->hasMany(Lesson::class) ;
    }

    public function parts(){
        return $this->hasMany(Part::class) ;
    }

}
