<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Track;
use App\Playlist;

class AddTracksToListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_add_tracks_to_a_resource()
    {
        $this->json('POST', '/api/playlists/1/tracks/999/add')
            ->assertStatus(401);
    }

    /** @test */
    public function a_user_can_add_tracks_to_his_playlist()
    {
        $this->signin();

        $playlist = create(Playlist::class, [ 'user_id' => auth()->id() ]);
        $track = create(Track::class);

        $this->json('POST', $playlist->path() . '/tracks/' . $track->id . '/add')
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'tracks',
                'id'   => (string) $track->id,
            ]]);

        $this->assertDatabaseHas('playlist_track', [
            'playlist_id' => $playlist->id,
            'track_id' => $track->id,
            'user_id'  => auth()->id(),
        ]);
    }
}
