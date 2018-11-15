<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Tag;

class FetchTagsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_fetch_all_tags()
    {
        $tags = create(Tag::class, [], 2);

        $this->json('GET', '/api/tags')
            ->assertStatus(200)
            ->assertJson(['data' => [
                [
                    'type' => 'tags',
                    'id'   => '1',
                ],
                [
                    'type' => 'tags',
                    'id'   => '2',
                ],
            ]]);
    }
}
