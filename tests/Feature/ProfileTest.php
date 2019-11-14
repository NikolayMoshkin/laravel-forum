<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_has_a_profile()
    {
        $this->withOutExceptionHandling();

        $user = factory(User::class)->create();
        $thread = factory(Thread::class)->create(['user_id' => $user->id]);

        $this->get('/profiles/' . $user->name)
            ->assertSee($user->name)
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

}
