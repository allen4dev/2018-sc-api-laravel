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
            'id'   => (string) $this->id,
            'attributes' => [
                'title' => $this->title,
                'published' => $this->published,
            ],
            'links' => [
                'self' => route('tracks.show', [ 'id' => $this->id ]),
            ],
            'relationships' => new TrackRelationshipsResource($this),
        ];
    }
}
