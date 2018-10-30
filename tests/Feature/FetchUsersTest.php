<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class FetchUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_fetch_users()
    {
        $user = create(User::class);

        $this->json('GET', $user->path())
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'users',
                'id'   => (string) $user->id,
            ]]);
    }
}
