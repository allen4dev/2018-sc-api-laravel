<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Album;

class AlbumTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_knows_his_path()
    {
        $album = create(Album::class);

        $this->assertEquals('/api/albums/1', $album->path());
    }
}
