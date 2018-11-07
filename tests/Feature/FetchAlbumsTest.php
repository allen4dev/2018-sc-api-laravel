<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Album;
use App\User;

class FetchAlbumsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_fetch_albums()
    {
        $album = create(Album::class);

        $this->json('GET', $album->path())
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'albums',
                'id'   => (string) $album->id,
            ]]);
    }

    /** @test */
    public function guests_can_fetch_the_published_albums_from_a_user()
    {
        $user = create(User::class);

        $published = create(Album::class, [
            'user_id'   => $user->id,
            'published' => true,
        ]);

        $notPublished = create(Album::class, [ 'user_id' => $user->id ]);

        $this->json('GET', $user->path() . '/albums')
            ->assertJson(['data' => [
                [
                    'type' => 'albums',
                    'id' => $published->id,
                ]
        ]]);
    }

    /** @test */
    public function guests_cannot_fetch_unpublished_albums_from_a_user()
    {
        // Given we have a user and a unpublished album created by him
        $user = create(User::class);

        $unpublishedAlbum = create(Album::class, [ 'user_id' => $user->id ]);

        // When someone tries to fetch the unpublished album
        $this->json('GET', $unpublishedAlbum->path())
        // Then he should receive a 403 UNAUTHORIZED
            ->assertStatus(403);        
    }
}
