<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp():void
    {
        parent::setUp();
        $this->actingAs(factory(User::class)->create());

    }

    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {
        $thread = factory(Thread::class)->create()->subscribe();

        $this->assertCount(0, auth()->user()->notifications); //используется метод notifications трейта Notifiable

        $thread->addReply([
            'user_id'=>auth()->id(),
            'body'=>'Some reply here'
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id'=>factory(User::class)->create()->id,
            'body'=>'Some reply here'
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /** @test */
    public function a_user_can_fetch_their_unread_notifications()
    {
        $this->withoutExceptionHandling();

        $thread = factory(Thread::class)->create()->subscribe();
        $thread->addReply([
            'user_id'=>factory(User::class)->create()->id,
            'body'=>'Some reply here'
        ]);

        $response = $this->get("/profiles/" . auth()->user()->name . "/notifications")->json();

        $this->assertCount(1, $response);

    }

    /** @test */
    public function a_user_can_mark_a_notification_as_read()
    {
        $this->withoutExceptionHandling();

        $thread = factory(Thread::class)->create()->subscribe();

        $thread->addReply([
            'user_id'=>factory(User::class)->create()->id,
            'body'=>'Some reply here'
        ]);

        $this->assertCount(1, auth()->user()->unreadNotifications); //unreadNotifications - метод трейта Notifiable

        $notificationId = auth()->user()->unreadNotifications->first()->id;
        $this->delete("/profiles/" . auth()->user()->name . "/notifications/{$notificationId}");

        $this->assertCount(0, auth()->user()->fresh()->unreadNotifications);
    }
}
