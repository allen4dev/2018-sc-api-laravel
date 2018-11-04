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
    public function a_user_can_publish_his_albums()
    {
        $this->signin();

        $album = create(Album::class, [ 'user_id' => auth()->id() ]);
        
        $this->json('PATCH', $album->path() . '/publish')
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'albums',
                'id'   => (string) $album->id,
            ]]);

        $this->assertDatabaseHas('albums', [
            'id' => $album->id,
            'user_id'   => auth()->id(),
            'published' => true,
        ]);
    }
}
