<?php

namespace Tests\Feature;

use App\Channel;
use App\User;
use App\Reply;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadsTest extends TestCase
{
    // Вносим изменения в phpunit.xml
    // ! при именовании класса обязательно добавлять слово "Test" !
    // В TestCase папке Unit можно добавлять собственнные методы для всех тестов

    use DatabaseMigrations;  //! используем трейт. Всякий раз, когда запускаем тест - используем migrate, а в конце все возвращаем обратно

    public function setUp():void  //здесь хранятся общие для тестов класса переменные
    {
        parent::setUp();

        $this->thread = factory(Thread::class)->create();
    }

    /** @test */   //обязательная строчка для выполнения теста
    public function a_user_can_browse_all_threads()
    {
        $response = $this->get('/threads');
        $response->assertSee($this->thread->title);  //должен увидеть

    }
    /** @test */
    public  function a_user_can_read_a_single_thread()
    {
        $response = $this->get($this->thread->path());
        $response->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_replies_connected_to_a_thread()
    {
        $reply = factory(Reply::class)->create(['thread_id' => $this->thread->id]);
        $response = $this->get($this->thread->path());
        $response->assertSee($reply->body);

    }

    /** @test */
    public function a_user_can_read_a_thread_according_to_a_tag()
    {
        $channel = factory(Channel::class)->create();
        $threadInChannel = factory(Thread::class)->create(['channel_id'=>$channel->id]);
        $threadNotInChannel = factory(Thread::class)->create();

        $this->get('/threads/'.$channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_any_username()
    {
        $this->actingAs(factory(User::class)->create(['name'=> 'JohnDoe']));

        $threadByJohn = factory(Thread::class)->create(['user_id' => auth()->id()]);
        $otherThread = factory(Thread::class)->create();

        $this->get('/threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($otherThread->title);

    }

    /** @test */
    public function a_user_can_filter_threads_by_popularity()
    {
        $threadWithTwoReplies = factory(Thread::class)->create();
        factory(Reply::class, 2)->create(['thread_id' => $threadWithTwoReplies->id]);

        $threadWithThreeReplies = factory(Thread::class)->create();
        factory(Reply::class, 3)->create(['thread_id' => $threadWithThreeReplies->id]);

        $threadWithNoReplies = $this->thread;

        $response = $this->getJson('threads?popular=1')->json();

        $this->assertEquals([3,2,0], array_column($response, 'replies_count'));
    }

    /** @test */
    public function a_user_can_filter_threads_by_unanswered_threads()
    {
        $this->withoutExceptionHandling();
        $thread = factory(Thread::class)->create();
        factory(Reply::class)->create(['thread_id' => $thread->id]);
        $this->get('threads?unanswered=1')
            ->assertSee($this->thread->title)
            ->assertDontSee($thread->title);
    }


}
