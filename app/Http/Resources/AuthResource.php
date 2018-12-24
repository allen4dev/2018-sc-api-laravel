<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class AuthResource extends JsonResource
{
    protected $token;

    public function __construct($resource, $token)
    {
        $this->token = $token;

        parent::__construct($resource);
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => 'auth',
            'id'  => (string) $this->id,
            'attributes' => [
                'token' => $this->token
            ],
            'relationships' => new AuthRelationshipsResource($this)
        ];
    }

    public function with($request)
    {
        return [
            'included' => [
                new UserResource($this)
            ]
        ];
    }
}
