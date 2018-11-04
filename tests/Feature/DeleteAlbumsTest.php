<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Album;

class DeleteAlbumsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_delete_his_albums()
    {
        $this->withoutExceptionHandling();

        $this->signin();

        $album = create(Album::class, [ 'user_id' => auth()->id() ]);

        $this->json('DELETE', $album->path())
            ->assertStatus(204);

        $this->assertDatabaseMissing('albums', [
            'id' => $album->id,
            'user_id' => auth()->id(),
        ]);
    }
}
