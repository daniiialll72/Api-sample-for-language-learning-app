<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'title',
        'description',
        'language_id',
        'period_id',
        'order_level',
    ];

    public function language(){
        return $this->belongsTo(Language::class) ;
    }

    public function period(){
        return $this->belongsTo(Period::class) ;
    }

    public function lessons(){
        return $this->hasMany(Lesson::class) ;
    }

    public function parts(){
        return $this->hasMany(Part::class) ;
    }
}
