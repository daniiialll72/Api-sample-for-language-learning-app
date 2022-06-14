<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'language_id',
        'part_id',
        'slider_id',
        'answer',
        'score',

    ];


    public function language(){
        return $this->belongsTo(Language::class) ;
    }

    public function part(){
        return $this->belongsTo(Part::class) ;
    }

    public function slider(){
        return $this->belongsTo(Slider::class) ;
    }

    public function user(){
        return $this->belongsTo(User::class) ;
    }

}
