<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Playlist;

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
}
