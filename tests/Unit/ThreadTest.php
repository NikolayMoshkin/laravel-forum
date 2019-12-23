<?php

namespace Tests\Unit;

use App\Channel;
use App\Notifications\ThreadWasUpdated;
use App\Reply;
use App\Thread;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp(): void
    {
        parent::setUp();

        $this->thread = factory(Thread::class)->create();
    }

    /** @test */
    public function a_thread_has_replies()
    {
        $this->assertInstanceOf(Collection::class, $this->thread->replies);
    }

    /** @test */
    public function a_thread_has_creator()
    {
        $this->assertInstanceOf(User::class, $this->thread->owner);
    }

    /** @test */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'foo',
            'user_id' => 1,
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    public function a_thread_notify_all_registered_subscibers_when_a_reply_is_added()
    {
        Notification::fake();   // позволяет симулировать отправку уведомлений в тестах (по почте, в базу данных)

        $this->actingAs(factory(User::class)->create());


        $this->thread
            ->subscribe()
            ->addReply([
                'body' => 'foo',
                'user_id' => 999,
            ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    /** @test */
    public function a_thread_belongs_to_a_channel()
    {
        $this->assertInstanceOf(Channel::class, $this->thread->channel);
    }

    /** @test */
    public function a_thread_has_a_path()
    {
        $this->assertEquals("/threads/" . $this->thread->channel->slug . "/" . $this->thread->id, $this->thread->path());
    }

    /** @test */
    public function a_thread_can_be_subscribed_to()
    {
        $this->actingAs(factory(User::class)->create());
        $this->thread->subscribe();
        $this->assertEquals(1, $this->thread->subscriptions()->where('user_id', auth()->id())->count());

    }

    /** @test */
    public function a_thread_can_be_unsubscribed_from()
    {
        $this->thread->subscribe($userId = 1);
        $this->thread->unsubscribe($userId);

        $this->assertEquals(0, $this->thread->subscriptions->count());

    }

    /** @test */
    public function it_knows_if_the_authenticated_user_is_subscribed_to_it()
    {
        $thread = factory(\App\Thread::class)->create();
        $this->actingAs(factory(\App\User::class)->create());

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe(auth()->id());

        $this->assertTrue($thread->isSubscribedTo);

    }

    /** @test */
    public function a_thread_can_check_if_the_authenticated_user_has_all_replies()
    {
        $this->actingAs(factory(User::class)->create());

        $thread = $this->thread;

        tap(auth()->user(), function ($user) use ($thread) {
            $this->assertTrue($thread->hasUpdatesFor($user));

            //Simulate that the user visited the thread
            $user->read($thread);

            $this->assertFalse($this->thread->hasUpdatesFor($user));
        });

    }
}
