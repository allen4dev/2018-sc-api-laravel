<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Track;
use App\Reply;

class FetchRepliesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_fetch_track_replies()
    {
        $track = create(Track::class);
        $reply = create(Reply::class, [ 'track_id' => $track->id ]);

        $replyForOtherTrack = create(Reply::class);

        $this->json('GET', $track->path() . '/replies')
            ->assertStatus(200)
            ->assertJson(['data' => [
                [
                    'type' => 'replies',
                    'id'   => (string) $reply->id,
                ]
            ]]);

    }
}
