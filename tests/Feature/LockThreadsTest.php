<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function once_locked_a_thread_may_not_receive_new_replies()
    {
        $this->actingAs(factory(User::class)->create());

        $thread = factory(Thread::class)->create();

        $thread->lock();

        $this->post($thread->path() . '/replies' ,[
            'body' => 'Foobar',
            'user_id' => auth()->id()
        ])->assertStatus(422);
    }

    /** @test */
    public function non_administrators_may_not_lock_threads()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(factory(User::class)->create());
        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);


        $this->post(route('locked-threads.store', $thread))
            ->assertRedirect(route('threads.index'));

        $this->assertFalse(!! $thread->fresh()->locked); //TODO: '!!' изменяет значение на boolean (true/false). По-умолчанию тип boolean в laravel - это 0 или 1
    }


    /** @test */
    public function administrators_can_lock_threads()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(factory(User::class)->states('administrator')->create());

        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread))
            ->assertStatus(200);

        $this->assertTrue(!! $thread->fresh()->locked , 'Failed asserting that the thread is locked');
    }
}
