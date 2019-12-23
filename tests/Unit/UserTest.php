<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;


class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_fetch_their_most_recent_reply()
    {
        $user = factory(User::class)->create();
        $reply = factory(Reply::class)->create([
            'user_id' => $user->id
        ]);

        $this->assertEquals($reply->id, $user->lastReply->id);
    }
}
