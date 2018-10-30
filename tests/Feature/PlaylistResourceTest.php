<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Playlist;

class PlaylistResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_contain_a_type_id_and_attributes_under_a_data_object()
    {
        $playlist = create(Playlist::class);

        $this->fetchPlaylist($playlist)
            ->assertJson([
                'data' => [
                    'type' => 'playlists',
                    'id'   => (string) $playlist->id,
                    'attributes' => [
                        'title' => $playlist->title
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_should_contain_a_links_object_with_a_self_url_link_under_a_data_object()
    {
        $playlist = create(Playlist::class);

        $this->fetchPlaylist($playlist)
            ->assertJson(['data' => [
                'links' => [ 'self' => route('playlists.show', [ 'id' => $playlist->id ]) ]
            ]]);
    }

    public function fetchPlaylist($playlist)
    {
        return $this->json('GET', $playlist->path());
    }
}
