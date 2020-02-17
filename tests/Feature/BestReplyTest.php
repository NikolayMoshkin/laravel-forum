<?php


namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Testing\Fakes\MailFake;
use Tests\TestCase;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;


    /** @test */
    public function a_thread_creator_make_mark_any_reply_as_best_reply()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(factory(User::class)->create());
        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $replies = factory(Reply::class, 2)->create([
            'thread_id' => $thread->id
        ]);

        $this->assertFalse($replies[1]->isBest());
        $this->postJson(route('best-replies.store', [$replies[1]->id]));

        $this->assertTrue($replies[1]->fresh()->isBest());

    }

    /** @test */
    public function only_a_thread_creator_may_mark_a_reply_as_best()
    {
        $this->actingAs(factory(User::class)->create());

        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $reply = factory(Reply::class)->create([
            'thread_id' => $thread->id
        ]);

        $this->actingAs(factory(User::class)->create());

        $this->postJson(route('best-replies.store', [$reply->id]))
            ->assertStatus(403);

        $this->assertFalse($reply->fresh()->isBest());

    }

    /** @test */
    public function if_a_best_reply_is_deleted_then_the_thread_is_properly_updated_to_reflect_that()
    {

        $this->actingAs(factory(User::class)->create());

        $reply = factory(Reply::class)->create(['user_id'=>auth()->id()]);

        $reply->thread->toggleBestReply($reply);

        $this->deleteJson(route('replies.destroy', $reply->id)); //TODO:Laravel automatically fetch the primary key of eloquent model and put in route-model binding

        $this->assertNull($reply->thread->fresh()->best_reply_id);

    }

}
