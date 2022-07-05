<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\URL;
use Illuminate\Http\Resources\Json\JsonResource;

class LevelResource extends JsonResource
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
            'language_name' => $this->language->description ?? '',
            'period_id' => $this->period_id,
            'period_title' => $this->period->title ?? '',
            'image' => $this->image,
            'title' => $this->title,
            'description' => $this->description,
            'order_level' => $this->order_level,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    
    }
}
