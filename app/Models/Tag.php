<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'tag',
    ];

    public function sliders(){
        return $this->belongsToMany(Sliders::class,'slider_tags');
    }
}
