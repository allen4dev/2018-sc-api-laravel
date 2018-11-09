<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Track;
use App\Reply;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_reply_tracks()
    {
        $this->json('POST', '/api/tracks/1/replies', [])
            ->assertStatus(401);
    }

    /** @test */
    public function a_user_can_reply_a_published_track()
    {
        $this->signin();

        $track = create(Track::class, [ 'published' => true ]);
        $details = [ 'body' => 'An awful song' ];

        $this->json('POST', $track->path() . '/replies', $details)
            ->assertStatus(201)
            ->assertJson(['data' => [
                'type' => 'replies',
                'id'   => '1',
            ]]);

        $this->assertDatabaseHas('replies', [
            'user_id' => auth()->id(),
            'body' => $details['body'],
            'replyable_id'   => $track->id,
            'replyable_type' => Track::class,
        ]);
    }

    /** @test */
    public function a_user_cannot_reply_unpublished_tracks()
    {
        $this->signin();

        $track = create(Track::class);

        $details = [ 'body' => 'An awful song' ];
        
        $this->json('POST', $track->path() . '/replies', $details)
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_reply_another_reply()
    {
        $this->signin();

        $reply = create(Reply::class);

        $details = raw(Reply::class);

        $this->json('POST', $reply->path() . '/reply', $details)
            ->assertStatus(201)
            ->assertJson(['data' => [
                'type' => 'replies',
                'id'   => '2',
            ]]);
        
        $this->assertDatabaseHas('replies', [
            'replyable_id'   => $reply->id,
            'replyable_type' => Reply::class,
            'user_id' => auth()->id(),
            'body'    => $details['body'],
        ]);
    }
}
