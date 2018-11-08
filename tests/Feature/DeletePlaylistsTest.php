<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Db;

use App\Playlist;
use App\Track;

class DeletePlaylistsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_delete_playlists()
    {
        $this->json('DELETE', '/api/playlists/1')
            ->assertStatus(401);
    }

    /** @test */
    public function a_user_can_delete_his_playlist()
    {
        $this->signin();

        $playlist = create(Playlist::class, [ 'user_id' => auth()->id() ]);

        $this->json('DELETE', $playlist->path())
            ->assertStatus(204);

        $this->assertDatabaseMissing('playlists', [
            'id' => $playlist->id,
            'user_id' => auth()->id(),
        ]);
    }

    /** @test */
    public function a_user_cannot_delete_other_users_playlists()
    {
        $this->signin();

        $playlist = create(Playlist::class);

        $this->json('DELETE', $playlist->path())
            ->assertStatus(403);

        $this->assertDatabaseHas('playlists', [
            'id' => $playlist->id,
            'user_id' => $playlist->user_id,
        ]);
    }

    /** @test */
    public function after_delete_a_playlist_the_playlist_track_table_should_not_have_a_record_with_this_playlist()
    {
        $this->signin();

        $track = create(Track::class, [ 'user_id' => auth()->id() ]);
        
        $playlist = create(Playlist::class, [ 'user_id' => auth()->id() ]);

        Db::table('playlist_track')->insert([
            'user_id' => auth()->id(),
            'playlist_id' => $playlist->id,
            'track_id' => $track->id,
        ]);

        $this->json('DELETE', $playlist->path());
        
        $this->assertDatabaseMissing('playlist_track', [
            'user_id' => auth()->id(),
            'playlist_id' => $playlist->id,
            'track_id' => $track->id,
        ]);
    }
}
