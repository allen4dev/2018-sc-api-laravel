<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Track;
use App\User;

class UpdateTracksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_update_tracks()
    {
        $this->json('PATCH', '/api/tracks/1', [])
            ->assertStatus(401);
    }

    /** @test */
    public function a_user_can_update_his_published_and_unpublished_tracks()
    {
        $this->signin();

        $track = create(Track::class, [ 'user_id' => auth()->id() ]);

        $newFields = [ 'title' => 'A better track title' ];

        $this->json('PATCH', $track->path(), $newFields)
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'tracks',
                'id'   => (string) $track->id,
                'attributes' => [
                    'title' => $newFields['title'],
                ],
            ]]);

        $this->assertDatabaseHas('tracks', [
            'user_id' => auth()->id(),
            'title' => $newFields['title'],
        ]);
    }

    /** @test */
    public function only_owners_can_update_a_track()
    {
        $this->signin();

        $track = create(Track::class);

        $this->json('PATCH', $track->path(), [ 'title' => 'Not my track' ])
            ->assertStatus(403);
    }
}
