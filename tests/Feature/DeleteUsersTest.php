<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_delete_his_profile()
    {
        $this->signin();

        $this->assertDatabaseHas('users', [
            'id' => auth()->id(),
            'email' => auth()->user()->email,
            'username' => auth()->user()->username,
        ]);

        $this->json('DELETE', auth()->user()->path())
            ->assertStatus(204);

        $this->assertDatabaseMissing('users', [
            'id' => auth()->id(),
            'email' => auth()->user()->email,
            'username' => auth()->user()->username,
        ]);
    }
}
