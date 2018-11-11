<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'type' => 'notifications',
            'id'   => (string) $this->id,
            'attributes' => [
                'message'     => $this->data['message'],
                'additional'  => [
                    'content' => $this->data['additional']['content'],
                    'sender_username' => $this->data['additional']['sender_username'],
                ],
                'action'     => explode('\\', $this->type)[2],
                'created_at' => (string) $this->created_at,
                'updated_at' => (string) $this->updated_at
            ],
            'links' => [
                'self' => route('notifications.show', [ 'id' => $this->id ]),
            ]
        ];
    }
}
