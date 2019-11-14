<?php

namespace Tests\Feature;

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
        $this->actingAs(factory(User::class)->create());
        $reply = factory(Reply::class)->make(['body'=>null]);
        $this->post($this->thread->path().'/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }
}
