<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Album;

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
}
