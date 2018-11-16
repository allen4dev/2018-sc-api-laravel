<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Http\UploadedFile;

use App\Tag;
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

        $tag = create(Tag::class, [ 'name' => 'jpop' ]);

        $photo = UploadedFile::fake()->image('my_track.jpg');

        $src = UploadedFile::fake()->create('song.mp3');

        $tags[] = $tag->id;

        $details = [
            'title' => 'My new Track',
            'photo' => $photo,
            'src'   => $src,
            'tags'  => $tags
        ];

        $this->json('POST', '/api/tracks', $details)
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'type' => 'tracks',
                    'id' => '1'
                ]
            ]);

        $this->assertDatabaseHas('tracks', [
            'title' => $details['title'],
            'user_id' => auth()->id(),
        ]);

        $this->assertDatabaseHas('taggables', [
            'tag_id' => $tag->id,
            'taggable_id'   => 1,
            'taggable_type' => Track::class,
        ]);
    }
}
