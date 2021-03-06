<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\DB;

use App\Playlist;
use App\Tag;
use App\Track;

class RemoveTracksFromPlaylistTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_remove_tracks_from_a_resource()
    {
        $this->json('DELETE', '/api/playlists/1/tracks/999/remove')
            ->assertStatus(401);
    }

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

    /** @test */
    public function after_remove_a_track_his_tags_should_also_be_removed()
    {
        $this->signin();

        $playlist = create(Playlist::class, [ 'user_id' => auth()->id() ]);
        
        $tag = create(Tag::class);

        $track = create(Track::class);

        Db::table('playlist_track')->insert([
            'user_id' => auth()->id(),
            'playlist_id' => $playlist->id,
            'track_id' => $track->id,
        ]);

        $this->json('DELETE', $playlist->path() . '/tracks/' . $track->id . '/remove')
            ->assertStatus(204);

        $this->assertDatabaseMissing('taggables', [
            'tag_id' => $tag->id,
            'taggable_id'   => $playlist->id,
            'taggable_type' => Playlist::class,
        ]);
    }
}
