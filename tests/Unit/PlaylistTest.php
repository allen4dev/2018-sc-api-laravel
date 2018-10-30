<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Playlist;

use App\User;

class PlaylistTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_knows_his_path()
    {
        $playlist = create(Playlist::class);

        $this->assertEquals('/api/playlists/1', $playlist->path());
    }

    /** @test */
    public function it_belongs_to_one_user()
    {
        $playlist = create(Playlist::class);

        $this->assertInstanceOf(User::class, $playlist->user);
    }
}
