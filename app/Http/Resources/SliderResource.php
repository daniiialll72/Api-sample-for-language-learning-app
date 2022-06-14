<?php

namespace App\Http\Resources;

use App\Http\Resources\TagResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
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
            'user_id' => $this->user_id,
            'part_id' => $this->part_id,
            'part_title' => $this->part->title ?? '',
            'kind' => $this->kind,
            'type' => $this->type,
            'title' => $this->title,
            'question' => $this->question,
            'description' => $this->description,
            'successmessage' => $this->successmessage,
            'failmessage' => $this->failmessage,
            'image' => $this->image,
            'voice' => $this->voice,
            'order_slider' => $this->order_slider,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'answers' => SlideranswerResource::collection($this->slideranswers),
            'tags' => TagResource::collection($this->tags),
        ];
    }
}
