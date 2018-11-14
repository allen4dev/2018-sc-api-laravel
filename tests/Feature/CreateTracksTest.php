<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Http\UploadedFile;

use App\Track;

class CreateTracksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_create_tracks()
    {
        $this->json('POST', '/api/tracks', [])
            ->assertStatus(401);
    }

    /** @test */
    public function a_user_can_create_tracks()
    {
        $this->signin();

        $photo = UploadedFile::fake()->image('my_track.jpg');

        $track = raw(Track::class, compact('photo'));

        $this->json('POST', '/api/tracks', $track)
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'type' => 'tracks',
                    'id' => '1'
                ]
            ]);

        $this->assertDatabaseHas('tracks', [
            'title' => $track['title'],
            'user_id' => auth()->id(),
        ]);
    }
}
