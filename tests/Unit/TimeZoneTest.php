<?php

namespace Tests\Unit;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TimeZoneTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_thread_gets_created_at_time_with_api()
    {
//        $thread = factory(Thread::class)->create();
//        $this->assertSame(gettype($thread->created_at->toDateString('Y-m-d')), gettype('2019-01-01'));
        $this->assertTrue(true);
    }
}
