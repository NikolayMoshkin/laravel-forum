<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadsTest extends TestCase
{
    // ! при именовании класса обязательно добавлять слово "Test" !

    //app/Exception/Handler.php В методе render перед return добавить: if(app()->environment() === 'testing') throw $exception;
    //чтобы сразу получать ошибку без рендера страницы ошибки

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


}
