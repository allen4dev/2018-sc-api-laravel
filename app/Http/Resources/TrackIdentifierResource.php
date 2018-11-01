<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrackIdentifierResource extends JsonResource
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
                'type' => 'tracks',
                'id'   => (string) $this->id,
            ],
            'links' => [ 'self' => route('tracks.show', [ 'id' => $this->id ]) ],
        ];
    }
}
