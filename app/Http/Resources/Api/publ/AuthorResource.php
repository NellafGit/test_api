<?php

namespace App\Http\Resources\Api\publ;

use App\Http\Resources\Api\publ\Collections\BookCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'year' => $this->year,
            'books' => BookResource::collection($this->books),
        ];
    }
}
