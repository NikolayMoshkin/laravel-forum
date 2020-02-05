<?php

namespace Tests\Feature;

use App\Activity;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;


class CreateThreadsTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function guest_may_not_create_thread()
    {
//        $this->withoutExceptionHandling();  //TODO:не рендерить страницу с обработчиком событий, а просто выдавать ошибку, если таковая имеется
//        $this->expectException('Illuminate\Auth\AuthenticationException'); // сравниваем ошибки (без строчки выше сранивать будет не с чем и тест не пройдет)
        $this->post('/threads/', [])
            ->assertRedirect(route('verification.notice'));

    }

    /** @test */
    public function authenticated_user_must_first_confirm_their_email_before_creating_threads()
    {
        $user = factory(User::class)->create(['email_verified_at' => null]);
        $this->actingAs($user);
        $thread = factory(Thread::class)->make();
        $this->post('/threads/', $thread->toArray())
            ->assertRedirect(route('verification.notice'));
    }

    /** @test */
    public function a_user_can_create_new_forum_thread()
    {
        $this->actingAs(factory(User::class)->create());  //то же самое, что be()

        $thread = factory(Thread::class)->make(); //метод ->raw() заменяет собой методы ->make()->toArray()

        $this->post('/threads/', $thread->toArray());

        $this->get('/threads/'.$thread->id)
            ->assertSee($thread->body)
            ->assertSee($thread->title);
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
//        $this->withoutExceptionHandling();
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
//        factory(Channel::class, 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }


    /** @test */
    public function a_thead_requires_a_unique_slug()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(factory(User::class)->create());

        $thread = factory(Thread::class)->create([
            'title' => 'Foo Title',
            'slug' => 'foo-title'
        ]);

        $this->post(route('threads.store'), $thread->toArray());
        $this->assertTrue(Thread::whereSlug('foo-title-2')->exists());

        $this->post(route('threads.store'), $thread->toArray());
        $this->assertTrue(Thread::whereSlug('foo-title-3')->exists());

    }
    /** @test */
    public function a_guest_can_not_delete_threads()
    {
        $thread = factory(Thread::class)->create();

        $this->json('DELETE', $thread->path())
            ->assertStatus(403);
    }

    /** @test */
    public function a_thread_can_be_deleted_by_owner()
    {
        $this->actingAs(factory(User::class)->create());

        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);
        $reply = factory(Reply::class)->create(['thread_id' => $thread->id]);

        $this->json('DELETE', $thread->path())
            ->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertEquals(0, Activity::count());

    }

}
