<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Track;

class PublishTracksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authorized_users_can_publish_his_tracks()
    {
        $this->withoutExceptionHandling();

        $this->signin();

        $track = create(Track::class, [ 'user_id' => auth()->id() ]);

        $this->publishTrack($track)
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'tracks',
                'id'   => (string) $track->id,
                'attributes' => [
                    'published' => true,
                ]
            ]]);

        $this->assertDatabaseMissing('tracks', [
            'id' => $track->id,
            'published' => false,
        ]);

        $this->assertDatabaseHas('tracks', [
            'id' => $track->id,
            'published' => true,
        ]);
    }

    /** @test */
    public function unauthorized_users_cannot_publish_tracks_from_other_users()
    {
        $this->signin();
        
        $track = create(Track::class);

        $this->publishTrack($track)
            ->assertStatus(403);
    }

    public function publishTrack($track)
    {
        return $this->json('PATCH', $track->path() . '/publish');
    }
}
