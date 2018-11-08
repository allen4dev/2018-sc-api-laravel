<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Db;

use App\Album;
use App\Playlist;
use App\Track;

class FavoritesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_favorite_a_resource()
    {
        $this->json('POST', '/api/tracks/1/favorite')
            ->assertStatus(401);
    }

    /** @test */
    public function guests_cannot_unfavorite_a_resource()
    {
        $this->json('POST', '/api/tracks/1/favorite')
            ->assertStatus(401);
    }

    /** @test */
    public function a_user_can_favorite_a_track()
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

    /** @test */
    public function a_user_cannot_favorite_a_resource_more_than_once()
    {
        $this->signin();

        $track = create(Track::class);

        try {
            $this->favoriteResource($track);
            $this->favoriteResource($track);
        } catch(Exception $e) {
            $this->fail('You cannot favorite a track more than once');
        }

        $this->assertCount(1, $track->favorites);
    }

    /** @test */
    public function a_user_can_favorite_a_playlist()
    {
        $this->signin();

        $playlist = create(Playlist::class);

        $this->favoriteResource($playlist)
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'playlists',
                'id'   => (string) $playlist->id,
            ]]);
        
        $this->assertDataBaseHas('favorites', [
            'user_id' => auth()->id(),
            'type'    => 'playlist',
            'favorited_id'   => $playlist->id,
            'favorited_type' => Playlist::class,
        ]);
    }

    /** @test */
    public function a_user_can_favorite_an_album()
    {
        $this->signin();

        $album = create(Album::class);

        $this->favoriteResource($album)
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'albums',
                'id'   => (string) $album->id,
            ]]);
        
        $this->assertDataBaseHas('favorites', [
            'user_id' => auth()->id(),
            'type'    => 'album',
            'favorited_id'   => $album->id,
            'favorited_type' => Album::class,
        ]);
    }

    /** @test */
    public function a_user_can_unfavorite_a_track()
    {
        $this->signin();

        $track = create(Track::class);

        Db::table('favorites')->insert([
            'user_id' => auth()->id(),
            'type' => 'track',
            'favorited_id'   => $track->id,
            'favorited_type' => Track::class,
        ]);

        $this->json('DELETE', $track->path() . '/unfavorite')
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'tracks',
                'id'   => (string) $track->id,
            ]]);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => auth()->id(),
            'type' => 'track',
            'favorited_id'   => $track->id,
            'favorited_type' => Track::class,
        ]);
    }

    /** @test */
    public function a_user_can_unfavorite_a_playlist()
    {
        $this->signin();

        $playlist = create(Playlist::class);

        Db::table('favorites')->insert([
            'user_id' => auth()->id(),
            'type' => 'playlist',
            'favorited_id'   => $playlist->id,
            'favorited_type' => Playlist::class,
        ]);

        $this->json('DELETE', $playlist->path() . '/unfavorite')
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'playlists',
                'id'   => (string) $playlist->id,
            ]]);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => auth()->id(),
            'type' => 'playlist',
            'favorited_id'   => $playlist->id,
            'favorited_type' => Playlist::class,
        ]);
    }

    /** @test */
    public function a_user_can_unfavorite_an_album()
    {   
        $this->signin();

        $album = create(Album::class, [ 'published' => true ]);

        Db::table('favorites')->insert([
            'user_id' => auth()->id(),
            'type' => 'albums',
            'favorited_id'   => $album->id,
            'favorited_type' => Album::class,
        ]);

        $this->json('DELETE', $album->path() . '/unfavorite')
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'albums',
                'id'   => (string) $album->id,
            ]]);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => auth()->id(),
            'type' => 'albums',
            'favorited_id'   => $album->id,
            'favorited_type' => Album::class,
        ]);
    }

    public function favoriteResource($resource)
    {
        return $this->json('POST', $resource->path() . '/favorite');
    }
}
