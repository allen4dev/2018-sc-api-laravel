<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Album;

class PublishAlbumsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_publish_albums()
    {
        $this->json('PATCH', '/api/albums/1/publish')
            ->assertStatus(401);
    }

    /** @test */
    public function a_user_can_publish_his_albums()
    {
        $this->signin();

        $album = create(Album::class, [ 'user_id' => auth()->id() ]);
        
        $this->json('PATCH', $album->path() . '/publish')
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'albums',
                'id'   => (string) $album->id,
                'attributes' => [
                    'published' => true,
                ]
            ]]);

        $this->assertDatabaseHas('albums', [
            'id' => $album->id,
            'user_id'   => auth()->id(),
            'published' => true,
        ]);
    }

    /** @test */
    public function a_user_cannot_publish_other_users_albums()
    {
        $this->signin();

        $album = create(Album::class);
        
        $this->json('PATCH', $album->path() . '/publish')
            ->assertStatus(403);

        $this->assertDatabaseHas('albums', [
            'id' => $album->id,
            'user_id'   => $album->user_id,
            'published' => false,
        ]);
    }
}
