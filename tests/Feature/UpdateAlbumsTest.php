<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Album;

class UpdateAlbumsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_update_albums()
    {
        $this->json('PATCH', '/api/albums/1', [])
            ->assertStatus(401);
    }

    /** @test */
    public function a_user_can_update_his_published_and_unpublished_albums()
    {
        $this->signin();

        $album = create(Album::class, [ 'user_id' => auth()->id() ]);

        $newFields = [ 'title' => 'A better album title' ];

        $this->json('PATCH', $album->path(), $newFields)
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'albums',
                'id'   => (string) $album->id,
                'attributes' => [
                    'title' => $newFields['title'],
                ],
            ]]);

        $this->assertDatabaseHas('albums', [
            'user_id' => auth()->id(),
            'title' => $newFields['title'],
        ]);
    }

    /** @test */
    public function only_owners_can_update_a_track()
    {
        $this->signin();

        $album = create(Album::class);

        $this->json('PATCH', $album->path(), [ 'title' => 'Not my album' ])
            ->assertStatus(403);
    }
}
