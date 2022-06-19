<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slideranswer extends Model
{
    use HasFactory;

    protected $fillable = [

        'slider_id',
        'answertext',
        'question',
        'image',
        'voice',

    ];


    public function slider(){
        return $this->belongsTo(Slider::class) ;
    }
}
