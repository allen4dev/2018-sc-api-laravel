<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class DeleteUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_delete_users_profiles()
    {
        $this->json('DELETE', '/api/users/1')
            ->assertStatus(401);
    }

    /** @test */
    public function a_user_can_delete_his_profile()
    {
        $this->signin();

        tap(auth()->user(), function ($user) {
            $this->assertDatabaseHas('users', [
                'id' => auth()->id(),
                'email' => $user->email,
                'username' => $user->username,
            ]);
    
            $this->json('DELETE', $user->path())
                ->assertStatus(204);
    
            $this->assertDatabaseMissing('users', [
                'id' => auth()->id(),
                'email' => $user->email,
                'username' => $user->username,
            ]);
        });
    }

    /** @test */
    public function only_the_owner_of_a_profile_can_delete_his_profile()
    {
        $this->signin();

        $user = create(User::class);
        
        $this->json('DELETE', $user->path())
            ->assertStatus(403);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $user->email,
            'username' => $user->username,
        ]);
    }
}
