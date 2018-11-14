<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Collection;

use App\Http\Transformers\IncludeTransformer;

use App\User;
use App\Album;
use App\Playlist;
use App\Track;

class UserResource extends JsonResource
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
            'type' => 'users',
            'id'   => (string) $this->id,
            'attributes' => [
                'username'   => $this->username,
                'email'      => $this->email,
                'fullname'   => $this->fullname,
                'avatar'          => $this->avatar,
                'profile_image'   => $this->profile_image,
                'created_at' => (string) $this->created_at,
                'updated_at' => (string) $this->updated_at,
                'time_since' => $this->created_at->diffForHumans(),
                'tracks_count'     => $this->tracks_count,
                'playlists_count'  => $this->playlists_count,
                'albums_count'     => $this->albums_count,
                'followers_count'  => $this->followers_count,
                'followings_count' => $this->followings_count,
            ]
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
            if ($include instanceof Album) {
                return new AlbumResource($include);
            }

            if ($include instanceof Playlist) {
                return new PlaylistResource($include);
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
