<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class TrendingThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var \App\Trending
     */

    private $trending;

    protected function setUp(): void
    {
        parent::setUp();

        $this->trending = new \App\Trending();
        $this->trending->clear();
    }

    /** @test */
    public function it_increments_a_threads_score_each_time_it_is_read()
    {
        $this->assertEmpty($this->trending->get());

        $thread = factory(Thread::class)->create();
        $this->call('GET', $thread->path()); //зашли на страницу поста

        $trending = $this->trending->get();
        $this->assertCount(1, $trending);
        $this->assertEquals($thread->title, $trending[0]->title);
    }

}
