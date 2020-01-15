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

    /** @test */
    public function a_user_can_determine_their_avatar_path()
    {
        $userWithAvatar = factory(User::class)->create(['avatar_path' => 'avatars/test.jpg']);
        $this->assertEquals(asset('storage/avatars/test.jpg'), $userWithAvatar->avatar());

        $userWithoutAvatar = factory(User::class)->create();
        $this->assertEquals(asset('storage/avatars/default.png'), $userWithoutAvatar->avatar());
    }
}
