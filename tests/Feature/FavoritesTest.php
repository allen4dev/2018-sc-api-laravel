<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Track;

class FavoritesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_favorite_a_tweet()
    {
        $this->signin();

        $track = create(Track::class);

        $this->favoriteResource($track)
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'tracks',
                'id'   => (string) $track->id,
            ]]);

        $this->assertDatabaseHas('favorites', [
            'user_id' => auth()->id(),
            'type'    => 'track',
            'favorited_id'   => $track->id,
            'favorited_type' => Track::class,
        ]);
    }

    public function favoriteResource($resource)
    {
        return $this->json('POST', $resource->path() . '/favorite');
    }
}
