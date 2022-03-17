<?php

namespace App\Http\Resources\Api\publ;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'book' => [
                'id' => $this->id,
                'title' => $this->title,
                'content' => $this->content,
                'publish_year' => $this->publish_year,
            ],
            'author' => $this->authors
        ];
    }
}
