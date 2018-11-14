<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Track;

class ReproducedTracksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_increment_the_reproduced_count()
    {
        $track = create(Track::class, [ 'published' => true ]);

        $this->assertEquals(0, $track->reproduced_count);

        $this->json('POST', $track->path() . '/increment')
            ->assertStatus(204);

        $this->assertEquals(1, $track->fresh()->reproduced_count);
    }
}
