<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_knows_his_path()
    {
        $user = create(User::class);

        $this->assertEquals('/api/users/1', $user->path());
    }

    /** @test */
    public function it_has_many_tracks()
    {
        $user = create(User::class);

        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Collection',
            $user->tracks
        );
    }

    /** @test */
    public function it_has_many_playlists()
    {
        $user = create(User::class);

        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Collection',
            $user->playlists
        );
    }

     /** @test */
    public function it_has_many_albums()
    {
        $user = create(User::class);

        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Collection',
            $user->albums
        );
    }
}
