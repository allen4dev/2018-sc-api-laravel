<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\DB;

use App\Playlist;
use App\Track;

class RemoveTracksFromListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_remove_a_track_from_his_playlist()
    {
        $this->signin();

        $playlist = create(Playlist::class, [ 'user_id' => auth()->id() ]);
        $track = create(Track::class);

        Db::table('playlist_track')->insert([
            'user_id' => auth()->id(),
            'playlist_id' => $playlist->id,
            'track_id' => $track->id,
        ]);

        $this->json('DELETE', $playlist->path() . '/tracks/' . $track->id . '/remove')
            ->assertStatus(204);

        $this->assertDatabaseMissing('playlist_track', [
            'user_id' => auth()->id(),
            'playlist_id' => $playlist->id,
            'track_id' => $track->id,
        ]);
    }
}
