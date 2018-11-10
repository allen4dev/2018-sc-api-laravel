<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReplyIdentifierResource extends JsonResource
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
            'data' => [
                'type' => 'replies',
                'id'   => (string) $this->id,
            ],
            'links' => [
                'self' => route('replies.show', [ 'id' => $this->id ]),
            ]
        ];
    }
}
