<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Collection;

use App\Http\Transformers\IncludeTransformer;

use App\Favorite;

class AlbumResource extends JsonResource
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
            'type' => 'albums',
            'id' => (string) $this->id,
            'attributes' => [
                'title' => $this->title,
                'published' => $this->published,
            ],
            'links' => [
                'self' => route('albums.show', ['id' => $this->id]),
            ],
            'relationships' => new AlbumRelationshipsResource($this),
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
            if ($include instanceof Favorite) {
                return new UserResource($include->user);
            }
        });
    }
}
