<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Album;

class CreateAlbumsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_create_albums()
    {
        $this->signin();

        $album = raw(Album::class);

        $this->json('POST', '/api/albums', $album)
            ->assertStatus(201)
            ->assertJson(['data' => [
                'type' => 'albums',
                'id'   => '1',
            ]]);

        $this->assertDatabaseHas('albums', [
            'id'    => 1,
            'title' => $album['title'],
        ]);
    }
}
