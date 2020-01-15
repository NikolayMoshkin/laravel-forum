<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AddAvatarTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function only_members_can_add_avatar()
    {
        $this->json('POST', 'api/users/1/avatar')
            ->assertStatus(401);
    }

    /** @test */
    public function a_valid_avatar_must_be_provided()
    {
//        $this->withoutExceptionHandlin();

        $this->actingAs(factory(User::class)->create());
        $this->json('POST', "api/users/".auth()->id()."/avatar",[
            'avatar' => 'not_an_image'
        ])->assertStatus(422);
    }

    /** @test */
    public function a_user_can_add_avatar_to_their_profile()
    {
        $this->actingAs(factory(User::class)->create());

        Storage::fake('public'); //TODO: имитируем в Storage папку public, сама очищается всякий раз

        $this->json('POST', "api/users/".auth()->id()."/avatar",[
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg') //TODO: имитируем фейковое изображение
        ]);

        $this->assertEquals('avatars/' . $file->hashName(), auth()->user()->avatar_path);

        Storage::disk('public')->assertExists('avatars/'.$file->hashName()); //TODO: получаем hash имя файла
    }
}
