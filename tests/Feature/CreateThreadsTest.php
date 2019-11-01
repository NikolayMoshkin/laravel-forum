<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function guest_may_not_create_thread()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->post('/threads/', []);
    }

    /** @test */
    public function an_authenticated_user_can_create_new_forum_thread()
    {
        $this->actingAs(factory(User::class)->create());  //то же самое, что be()

        $thread = factory(Thread::class)->make(); //метод ->raw() заменяет собой методы ->make()->toArray()

        $this->post('/threads/', $thread->toArray());

        $this->get('/threads/'.$thread->id)
            ->assertSee($thread->body)
            ->assertSee($thread->title);
    }
}
