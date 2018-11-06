<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Playlist;
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
}
