<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Album;
use App\User;

class AlbumTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_knows_his_path()
    {
        $album = create(Album::class);

        $this->assertEquals('/api/albums/1', $album->path());
    }

    /** @test */
    public function it_belongs_to_one_user()
    {
        $album = create(Album::class);

        $this->assertInstanceOf(User::class, $album->user);
    }
}
