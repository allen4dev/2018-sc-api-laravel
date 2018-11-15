<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Tag;

class TagResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_contain_a_type_id_and_attributes_under_a_data_object()
    {
        $tag = create(Tag::class);

        $this->json('GET', $tag->path())
            ->assertJson([
                'data' => [
                    'type' => 'tags',
                    'id'   => (string) $tag->id,
                    'attributes' => [
                        'name'      => $tag->name,
                        'created_at' => (string) $tag->created_at,
                        'updated_at' => (string) $tag->updated_at,
                        'time_since' => $tag->created_at->diffForHumans(),
                    ]
                ]
            ]);
    }
}
