<?php

namespace Tests\Feature;

use App\Favourite;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->thread = factory(Thread::class)->create();
    }

    /** @test */
    public function unauthenticated_user_may_not_add_replies()
    {
        $this->withoutExceptionHandling();
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->post($this->thread->path().'/replies', []);
    }
    /** @test */
    public function an_authenticated_user_can_participate_in_forum_threads()
    {
        $this->be(factory(User::class)->create());  //метод be() создает аутентифицированного пользователя (контракт)
        $reply = factory(Reply::class)->make();

        $this->post($this->thread->path().'/replies', $reply->toArray());  //отправляем post запрос с параметрами $reply
        $this->get($this->thread->path())->assertSee($reply->body); //переходим по адресу и должны увидеть
    }

    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(factory(User::class)->create());

        $this->expectException(\Exception::class);
        $reply = factory(Reply::class)->make(['body'=>null]);
        $this->post($this->thread->path().'/replies', $reply->toArray());
    }

    /** @test */
    public function an_authenticated_user_can_delete_his_replies()
    {
        $this->actingAs(factory(User::class)->create());
        $reply = factory(Reply::class)->create(['user_id' => auth()->id()]);

        $this->post('/replies/' . $reply->id . '/favourites');

        $this->json('DELETE', '/replies/'.$reply->id)
            ->assertStatus(204);

        $this->assertEquals(0, Reply::count());
        $this->assertEquals(0, Favourite::count());
        $this->assertDatabaseMissing('activities', ['subject_type' => 'App\Favourite']);
    }

    /** @test */
    public function replies_that_contain_spam_may_not_be_created()
    {
        $this->actingAs(factory(User::class)->create());

        $reply = factory(\App\Reply::class)->make(['body' => 'Это спам']);

//        $this->expectException(\Exception::class);

        $this->post($this->thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors();
    }

    /** @test */
    public function users_may_only_repy_a_maximum_of_once_per_minute()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(factory(User::class)->create());
        $reply = factory(\App\Reply::class)->make([
            'user_id' => auth()->id(),
            'body' => 'Simple reply'
        ]);

        $this->post($this->thread->path() . '/replies', $reply->toArray())
            ->assertRedirect();

        $this->post($this->thread->path() . '/replies' , $reply->toArray())
            ->assertSessionHasErrors();

    }
}
