<?php

namespace Tests\Feature;

use App\Activity;
use App\Favourite;
use App\User;
use App\Reply;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FavouriteTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reply = factory(Reply::class)->create();
    }


    /** @test */
    public function unauthenticated_user_can_not_favourite_replies()
    {
        $this->withoutExceptionHandling();
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->post('/replies/' . $this->reply->id . '/favourites');

    }

    /** @test */
    public function an_authenticated_user_can_favourite_any_replies()
    {
        $this->actingAs(factory(User::class)->create());

        $this->post('/replies/' . $this->reply->id . '/favourites');

        $this->assertCount(1, $this->reply->favourites);
    }

    /** @test */
    public function an_authenticated_user_may_favourite_a_reply()
    {
        $this->actingAs(factory(User::class)->create());
        $this->post('/replies/' . $this->reply->id . '/favourites');
        $this->assertCount(1, $this->reply->favourites);

    }

    /** @test */
    public function an_authenticated_user_may_unfavourite_a_reply()
    {
        $this->actingAs(factory(User::class)->create());
        $this->post('/replies/' . $this->reply->id . '/favourites');

        $this->post('/replies/' . $this->reply->id . '/favourites')
            ->assertSee(0);

        $this->assertEquals(0, Activity::count());


    }
}
