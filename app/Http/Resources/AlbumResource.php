<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Collection;

use App\Http\Transformers\IncludeTransformer;

use App\Favorite;
use App\Shared;
use App\Tag;
use App\Track;
use App\User;

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
                'photo' => $this->photo,
                'published' => $this->published,
                'created_at' => (string) $this->created_at,
                'updated_at' => (string) $this->updated_at,
                'time_since' => $this->created_at->diffForHumans(),
                'favorited_count' => $this->favorited_count,
                'tracks_count'    => $this->tracks_count,
                'shared_count'    => $this->shared_count,
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

            if ($include instanceof Shared) {
                return new UserResource($include->user);
            }

            if ($include instanceof Tag) {
                return new TagResource($include);
            }

            if ($include instanceof Track) {
                return new TrackResource($include);
            }

            if ($include instanceof User) {
                return new UserResource($include);
            }
        });
    }
}
