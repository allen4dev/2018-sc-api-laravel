<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Reply;
use App\Track;
use App\User;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_knows_his_path()
    {
        $reply = create(Reply::class);

        $this->assertEquals('/api/replies/1', $reply->path());
    }

    /** @test */
    public function it_belongs_to_a_track()
    {
        $reply = create(Reply::class);

        $this->assertInstanceOf(Track::class, $reply->replyable);
    }

    /** @test */
    public function it_belongs_to_a_reply()
    {
        $replied = create(Reply::class);

        $reply = create(Reply::class, [
            'replyable_id' => $replied->id,
            'replyable_type' => Reply::class,
        ]);

        $this->assertInstanceOf(Reply::class, $reply->replyable);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $reply = create(Reply::class);

        $this->assertInstanceOf(User::class, $reply->user);
    }
}
