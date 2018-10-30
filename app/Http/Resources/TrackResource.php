<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrackResource extends JsonResource
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
            'type' => 'tracks',
            'id'   => $this->id,
            'attributes' => [
                'name' => $this->name,
                'published' => $this->published,
            ],
            'links' => [
                'self' => route('tracks.show', [ 'id' => $this->id ]),
            ]
        ];
    }
}
