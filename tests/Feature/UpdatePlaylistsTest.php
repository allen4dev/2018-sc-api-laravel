<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Playlist;

class UpdatePlaylistsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_update_playlists()
    {
        $this->json('PATCH', '/api/playlists/1', [])
            ->assertStatus(401);
    }

    /** @test */
    public function a_user_can_update_his_playlists()
    {
        $this->signin();

        $playlist = create(Playlist::class, [ 'user_id' => auth()->id() ]);

        $newFields = [ 'title' => 'A better playlist title' ];

        $this->json('PATCH', $playlist->path(), $newFields)
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'playlists',
                'id'   => (string) $playlist->id,
                'attributes' => [
                    'title' => $newFields['title'],
                ],
            ]]);

        $this->assertDatabaseHas('playlists', [
            'user_id' => auth()->id(),
            'title' => $newFields['title'],
        ]);
    }

    /** @test */
    public function only__owners_can_update_a_playlist()
    {
        $this->signin();

        $playlist = create(Playlist::class);

        $this->json('PATCH', $playlist->path(), [ 'title' => 'Not my track' ])
            ->assertStatus(403);
    }
}
