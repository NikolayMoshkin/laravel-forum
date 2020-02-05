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
        $thread = factory(Thread::class)->create();

        $replies = factory(Reply::class, 2)->create([
            'thread_id' => $thread->id
        ]);

        $this->post(route('best-replies.store', [$replies[1]->id]));

        $this->assertTrue($replies[1]->isBest);


    }

}
