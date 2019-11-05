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

    /** @test */
    public function unauthenticated_user_may_not_add_replies()
    {
        $thread = factory(Thread::class)->create();
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->post($thread->path().'/replies', []);
    }
    /** @test */
    public function an_authenticated_user_can_participate_in_forum_threads()
    {
        $this->be(factory(User::class)->create());  //метод be() создает аутентифицированного пользователя (контракт)
        $thread = factory(Thread::class)->create();
        $reply = factory(Reply::class)->make();

        $this->post($thread->path().'/replies', $reply->toArray());  //отправляем post запрос с параметрами $reply
        $this->get($thread->path())->assertSee($reply->body); //переходим по адресу и должны увидеть
    }

}
