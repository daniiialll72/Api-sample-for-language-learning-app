<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\URL;
use Illuminate\Http\Resources\Json\JsonResource;

class PartResource extends JsonResource
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
            'language_id' => $this->language_id,
            'language_name' => $this->language->description,
            'period_id' => $this->period_id,
            'period_title' => $this->period->title ?? '',
            'level_id' => $this->level_id,
            'level_title' => $this->level->title ?? '',
            'lesson_id' => $this->lesson_id,
            'lesson_title' => $this->lesson->title ?? '',
            'image' => (URL::asset('storage/'.$this->image)),
            'title' => $this->title,
            'description' => $this->description,
            'order_parts' => $this->order_parts,
            'hasvocab' => $this->hasvocab,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
