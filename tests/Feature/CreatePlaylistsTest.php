<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Http\UploadedFile;

use App\Playlist;

class CreatePlaylistsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_create_playlists()
    {
        $this->json('POST' ,'api/playlists', [])
            ->assertStatus(401);
    }

    /** @test */
    public function a_user_can_create_playlists()
    {
        $this->signin();

        $photo = UploadedFile::fake()->image('photo.jpg');

        $details = raw(Playlist::class, [
            'user_id' => auth()->id(),
            'photo' => $photo
        ]);

        $this->json('POST', '/api/playlists', $details)
            ->assertStatus(201)
            ->assertJson(['data' => [
                'type' => 'playlists',
                'id'   => '1',
            ]]);
        
        $this->assertDatabaseHas('playlists', [
            'title'    => $details['title'],
            'user_id' => auth()->id(),
        ]);
    }
}
