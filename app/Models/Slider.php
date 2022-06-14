<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [

        'id',
        'user_id',
        'part_id',
        'kind',
        'type',
        'title',
        'description',
        'oriented',
        'answer',
        'question',
        'successmessage',
        'failmessage',
        'image',
        'voice',
        'radio_option',
        'order_slider',

    ];

    public function slideranswers()
    {
        return $this->hasMany(Slideranswer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class,'slider_tags');
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}
