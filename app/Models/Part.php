<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasFactory;
    protected $fillable = [
        'image',
        'title',
        'description',
        'language_id',
        'period_id',
        'level_id',
        'lesson_id',
        'order_parts',
        'hasvocab',
    ];

    public function language(){
        return $this->belongsTo(Language::class) ;
    }

    public function period(){
        return $this->belongsTo(Period::class) ;
    }

    public function level(){
        return $this->belongsTo(Level::class) ;
    }

    public function lesson(){
        return $this->belongsTo(Lesson::class) ;
    }

    public function vokabewords(){

        return $this->hasMany(Vokabeword::class);

    }

}
