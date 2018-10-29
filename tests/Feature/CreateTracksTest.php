<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTracksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_create_tracks()
    {
        $this->json('POST', '/api/tracks', [])
            ->assertStatus(401);
    }

    /** @test */
    public function a_user_can_create_tracks()
    {
        $this->signin();

        $track = [ 'name' => 'My awesome track' ];

        $this->json('POST', '/api/tracks', $track)
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'type' => 'tracks',
                    'id' => '1'
                ]
            ]);

        $this->assertDatabaseHas('tracks', [
            'name' => $track['name'],
            'user_id' => auth()->id(),
        ]);
    }
}
