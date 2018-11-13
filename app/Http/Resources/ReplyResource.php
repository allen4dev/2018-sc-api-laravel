<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReplyResource extends JsonResource
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
            'type' => 'replies',
            'id'   => (string) $this->id,
            'attributes' => [
                'body' => $this->body,
                'created_at' => (string) $this->created_at,
                'updated_at' => (string) $this->updated_at,
                'time_since' => $this->created_at->diffForHumans(),
            ],
            'links' => [
                'self' => route('replies.show', [ 'id' => $this->id ]),
            ],
            'relationships' => new ReplyRelationshipsResource($this),
        ];
    }
}
