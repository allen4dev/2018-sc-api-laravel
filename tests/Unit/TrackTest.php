<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;
use App\Track;

class TrackTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_path()
    {
        $track = create(Track::class);

        $this->assertEquals("/api/tracks/{$track->id}", $track->path());
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $track = create(Track::class);

        $this->assertInstanceOf(User::class, $track->user);
    }
}
