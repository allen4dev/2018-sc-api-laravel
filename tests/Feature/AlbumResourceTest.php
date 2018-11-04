<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Album;

class AlbumResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_contain_a_type_id_and_attributes_under_a_data_object()
    {
        $album = create(Album::class);

        $this->fetchAlbum($album)
            ->assertJson([
                'data' => [
                    'type' => 'albums',
                    'id'   => (string) $album->id,
                    'attributes' => [
                        'title' => $album->title,
                        'published' => $album->published,
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_should_contain_a_links_object_with_a_self_url_link_under_a_data_object()
    {
        $album = create(Album::class);

        $this->fetchAlbum($album)
            ->assertJson(['data' => [
                'links' => [ 'self' => route('albums.show', ['id' => $album->id]) ]
            ]]);
    }

    /** @test */
    public function it_should_contain_a_relationships_object_under_data_containing_identifiers_for_his_related_information()
    {
        $this->signin();

        $album = create(Album::class, [ 'user_id' => auth()->id() ]);
        
        $this->fetchAlbum($album)
            ->assertJson(['data' => [
                'relationships' => [
                    'user' => [
                        'data' => [
                            'type' => 'users',
                            'id'   => (string) auth()->id(),
                        ]
                    ]
                ]
            ]]);
    }

    public function fetchAlbum($album)
    {
        return $this->json('GET', $album->path());
    }
}
