<?php

namespace Tests;


use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function publishThread($override) //TODO:пользовательские методы для тестов можно добавлять здесь
    {
        $this->actingAs(factory(User::class)->create());
        $thread = factory(Thread::class)->make($override);
        return $this->post('/threads', $thread->toArray());
    }
}
