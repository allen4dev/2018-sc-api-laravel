<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AlbumCollection extends ResourceCollection
{
    protected $route;

    public function __construct($resource, $route)
    {
        $this->route = $route;

        parent::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => AlbumResource::collection($this->collection),
            'links' => [
                'self' => $this->route,
            ],
        ];
    }
}
