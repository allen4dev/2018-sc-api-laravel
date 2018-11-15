<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
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
            'type' => 'tags',
            'id'   => (string) $this->id,
            'attributes' => [
                'name' => $this->name,
                'playlists_count' => $this->playlists_count,
                'albums_count' => $this->albums_count,
                'tracks_count' => $this->tracks_count,
                'created_at'   => (string) $this->created_at,
                'updated_at'   => (string) $this->updated_at,
                'time_since'   => $this->created_at->diffForHumans(),
            ]
        ];
    }
}
