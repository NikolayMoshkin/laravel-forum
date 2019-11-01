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
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->post('/threads/1/replies', []);
    }
    /** @test */
    public function an_authenticated_user_can_participate_in_forum_threads()
    {
        $this->be(factory(User::class)->create());  //метод be() создает аутентифицированного пользователя (контракт)
        $thread = factory(Thread::class)->create();
        $reply = factory(Reply::class)->make();

        $this->post('/threads/'.$thread->id.'/replies', $reply->toArray());  //отправляем post запрос с параметрами $reply
        $this->get('/threads/'.$thread->id)->assertSee($reply->body); //переходим по адресу и должны увидеть
    }

}
