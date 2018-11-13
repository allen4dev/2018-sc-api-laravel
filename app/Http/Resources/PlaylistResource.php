<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Transformers\IncludeTransformer;

use Illuminate\Support\Collection;

use App\User;

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
            ],
            'relationships' => new PlaylistRelationshipsResource($this),
        ];
    }

    public function with($request)
    {
        if (! $request->include) return [];

        $includes = IncludeTransformer::includeData($this->resource, $request->include);
        
        return [
            'included' => $this->withIncluded($includes->unique()),
        ];
    }

    public function withIncluded(Collection $included)
    {
        return $included->map(function ($include) {
            if ($include instanceof User) {
                return new UserResource($include);
            }
        });
    }
}
