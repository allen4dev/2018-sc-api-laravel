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
        $album = create(Album::class, [ 'published' => true ]);

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
    public function a_user_can_fetch_his_unpublished_albums()
    {
        $this->signin();

        $album = create(Album::class, [ 'user_id' => auth()->id() ]);

        $this->json('GET', $album->path())
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'albums',
                'id'   => (string) $album->id,
            ]]);
    }

    /** @test */
    public function only_the_owner_of_an_album_can_fetch_his_unpublished_album()
    {
        $this->signin();

        $unpublishedAlbum = create(Album::class);

        $this->json('GET', $unpublishedAlbum->path())
            ->assertStatus(403);        
    }
}
