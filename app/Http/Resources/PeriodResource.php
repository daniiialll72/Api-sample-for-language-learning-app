<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\URL;
use Illuminate\Http\Resources\Json\JsonResource;

class PeriodResource extends JsonResource
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
            'image' => (URL::asset('storage/'.$this->image)),
            'title' => $this->title,
            'description' => $this->description,
            'order_period' => $this->order_period,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
