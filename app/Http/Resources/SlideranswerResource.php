<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\URL;
use Illuminate\Http\Resources\Json\JsonResource;

class SlideranswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slider_id' => $this->slider_id,
            'answertext' => $this->answertext,
            'image' => (URL::asset('storage/'.$this->image)),
            'voice' => (URL::asset('storage/'.$this->voice)),
            'question' => $this->question,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
