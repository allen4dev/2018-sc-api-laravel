<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

use App\Track;

class ReplyCollection extends ResourceCollection
{
    protected $track;

    public function __construct($resource, Track $track)
    {
        $this->track = $track;

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
            'data' => ReplyResource::collection($this->collection),
            'links' => [
                'self' => route('replies.index', [ 'id' => $this->track->id ])
            ]
        ];
    }
}
