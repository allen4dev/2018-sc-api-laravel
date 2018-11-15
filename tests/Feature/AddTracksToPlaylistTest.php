<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Db;

use App\Playlist;
use App\Tag;
use App\Track;

class AddTracksToPlaylistTest extends TestCase
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

    /** @test */
    public function after_add_a_track_his_tags_should_also_being_added_as_a_playlist_tags()
    {
        $this->signin();

        $playlist = create(Playlist::class, [ 'user_id' => auth()->id() ]);
        
        $tag = create(Tag::class);

        $track = create(Track::class, [ 'published' => true ]);

        Db::table('taggables')->insert([
            'tag_id' => $tag->id,
            'taggable_id'   => $track->id,
            'taggable_type' => Track::class,
        ]);

        $this->json('POST', $playlist->path() . '/tracks/' . $track->id . '/add');

        $this->assertDatabaseHas('taggables', [
            'tag_id' => $tag->id,
            'taggable_id'   => $playlist->id,
            'taggable_type' => Playlist::class,
        ]);
    }
}
