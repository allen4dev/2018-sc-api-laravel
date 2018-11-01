<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Track;

class ReplyTracksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_reply_tracks()
    {
        $this->json('POST', '/api/tracks/1/replies', [])
            ->assertStatus(401);
    }

    /** @test */
    public function a_user_can_reply_a_track()
    {
        $this->signin();

        $track = create(Track::class);
        $details = [ 'body' => 'An awful song' ];

        $this->json('POST', $track->path() . '/replies', $details)
            ->assertStatus(201)
            ->assertJson(['data' => [
                'type' => 'replies',
                'id'   => '1',
            ]]);

        $this->assertDatabaseHas('replies', [
            'track_id' => $track->id,
            'body'     => $details['body']
        ]);
    }
}
