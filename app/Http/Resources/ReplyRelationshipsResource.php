<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReplyRelationshipsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = $this->replyable;

        $name = strtolower((new \ReflectionClass($resource))->getShortName());

        return [
            'user' => new UserIdentifierResource($this->user),
            $name => $this->getIdentifier($name, $resource),
        ];
    }

    public function getIdentifier($name, $resource)
    {
        if ($name === 'track') {
            $identifier = new TrackIdentifierResource($resource);
        } else {
            $identifier = new ReplyIdentifierResource($resource);
        }

        return $identifier;
    }
}
