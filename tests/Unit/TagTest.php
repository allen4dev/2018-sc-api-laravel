<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Tag;

class TagTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_knows_his_path()
    {
        $tag = create(Tag::class);

        $this->assertEquals('/api/tags/1', $tag->path());
    }

    /** @test */
    public function a_tag_has_many_tracks()
    {
        $tag = create(Tag::class);

        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Collection',
            $tag->tracks
        );
    }
}
