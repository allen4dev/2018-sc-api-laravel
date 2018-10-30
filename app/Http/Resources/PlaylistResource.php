<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlaylistResource extends JsonResource
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
            'type' => 'playlists',
            'id'   => (string) $this->id,
            'attributes' => [
                'title' => $this->title,
            ],
            'links' => [
                'self' => route('playlists.show', [ 'id' => $this->id ]),
            ]
        ];
    }
}
