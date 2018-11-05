<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Playlist;
use App\User;

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

    /** @test */
    public function it_should_contain_a_relationships_object_under_data_containing_identifiers_for_his_related_information()
    {
        $this->signin();

        $playlist = create(Playlist::class, [ 'user_id' => auth()->id() ]);

        $this->fetchPlaylist($playlist)
            ->assertJson(['data' => [
                'relationships' => [
                    'user' => [
                        'data' => [ 'type' => 'users', 'id' => (string) auth()->id() ]
                    ]
                ]]
            ]);
    }

    /** @test */
    public function a_collection_should_contain_a_list_of_playlist_resources_under_a_data_object()
    {
        $this->withoutExceptionHandling();

        $user = create(User::class);

        create(Playlist::class, [ 'user_id' => $user->id ], 2);

        $this->json('GET', $user->path() . '/playlists')
            ->assertJson(['data' => [
                [
                    'type' => 'playlists',
                    'id' => '1',
                ],
                [
                    'type' => 'playlists',
                    'id' => '2',
                ],
            ]]);
    }

    public function fetchPlaylist($playlist)
    {
        return $this->json('GET', $playlist->path());
    }
}
