<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Db;

use App\Playlist;
use App\Tag;
use App\User;

class FetchPlaylistsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_fetch_playlists()
    {
        $playlist = create(Playlist::class);

        $this->json('GET', $playlist->path())
        ->assertStatus(200)
        ->assertJson(['data' => [
            'type' => 'playlists',
            'id'   => (string) $playlist->id,
        ]]);
    }

    /** @test */
    public function guests_can_fetch_all_playlists_from_a_user()
    {
        $user = create(User::class);

        $playlistsByUser = create(Playlist::class, [ 'user_id' => $user->id ], 2);
        $playlistNotByUser = create(Playlist::class);

        $this->json('GET', $user->path() . '/playlists')
            ->assertStatus(200)
            ->assertJson(['data' => [
                [
                    'type' => 'playlists',
                    'id'   => '1',
                ],
                [
                    'type' => 'playlists',
                    'id'   => '2',
                ],
            ]]);
    }

    /** @test */
    public function guests_can_fetch_all_playlists_related_to_a_tag()
    {
        $tag = create(Tag::class);

        $playlist = create(Playlist::class);
        $notRelatedPlaylist = create(Playlist::class);

        Db::table('taggables')->insert([
            'tag_id' => $tag->id,
            'taggable_id'   => $playlist->id,
            'taggable_type' => Playlist::class,
        ]);

        $this->json('GET', $tag->path() . '/playlists')
            ->assertStatus(200)
            ->assertJson(['data' => [
                [
                    'type' => 'playlists',
                    'id'   => (string) $playlist->id,
                ],
            ]]);
    }
}
