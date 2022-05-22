<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LanguageResource extends JsonResource
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

            'languagemother_id' => $this->languagemother_id,
            'languagemother_name' => $this->languagemother->description,
            'languagemother_image' => $this->languagemother->image,
            'languagemother_abbr' => $this->languagemother->shortdescription,
            
            'image' => $this->image,
            'shortdescription' => $this->shortdescription,
            'description' => $this->description,
            'explainlanguage' => $this->explainlanguage,
            'order_language' => $this->order_language,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
