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
    public function guests_can_fetch_the_albums_from_a_user()
    {
        $this->withoutExceptionHandling();

        // Given we have a user and two albums created by him
        $user = create(User::class);

        $albumByUser = create(Album::class, [ 'user_id' => $user->id ]);
        $albumNotByUser = create(Album::class);

        // When someone retrieves the user albums
        $this->json('GET', $user->path() . '/albums')
        // Then he should receive a 200 OK and JSON with aan array of Album Resources
            ->assertJson(['data' => [
                [
                    'type' => 'albums',
                    'id' => $albumByUser->id,
                ]
           ]]);
    }
}
