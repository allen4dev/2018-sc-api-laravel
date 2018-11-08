<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Track;
use App\User;

class FetchTracksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_fetch_published_tracks()
    {
        $published = create(Track::class, [ 'published' => true ]);
        $unpublished = create(Track::class);

        $response = $this->json('GET', $published->path())
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'type' => 'tracks',
                    'id'   => (string) $published->id,
                ]
            ]);
    }

    /** @test */
    public function a_user_can_fetch_his_unpublished_tracks()
    {
        $this->signin();

        $track = create(Track::class, [ 'user_id' => auth()->id() ]);

        $this->json('GET', $track->path())
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'tracks',
                'id'   => (string) $track->id,
            ]]);
    }

    /** @test */
    public function only_the_owner_of_a_track_can_fetch_his_unpublished_track()
    {
        $this->signin();

        $track = create(Track::class);

        $this->json('GET', $track->path())
            ->assertStatus(403);
    }

    /** @test */
    public function guests_can_fetch_all_published_tracks_from_a_user()
    {
        $user = create(User::class);

        $publishedTrack    = create(Track::class, [ 'user_id' => $user->id, 'published' => true ]);
        $unpublishedTracks = create(Track::class, [ 'user_id' => $user->id ], 2);

        $this->json('GET', $user->path() . '/tracks')
            ->assertStatus(200)
            ->assertJson(['data' => [
                [
                    'type' => 'tracks',
                    'id'   => '1',
                ],
            ]]);
    }
}
