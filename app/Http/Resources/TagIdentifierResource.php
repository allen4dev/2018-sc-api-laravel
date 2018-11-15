<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TagIdentifierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'type' => 'tags',
                'id'   => (string) $this->id,
            ],
            'links' => [ 'self' => route('tags.show', [ 'id' => $this->id ]) ],
        ];
    }
}
