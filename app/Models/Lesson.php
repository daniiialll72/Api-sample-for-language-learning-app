<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'title',
        'description',
        'language_id',
        'period_id',
        'level_id',
        'order_lesson',
        'freeornot',
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

    public function parts(){
        return $this->hasMany(Part::class) ;
    }

}
