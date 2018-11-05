<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TrackCollection extends ResourceCollection
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
        $route = $this->route; 

        return [
            'data'  => TrackResource::collection($this->collection),
            'links' => [ 'self' => $route ]
        ];
    }
}
