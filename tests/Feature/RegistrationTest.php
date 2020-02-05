<?php


namespace Tests\Feature;

use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Testing\Fakes\MailFake;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;


    /** @test */
    public function a_confirmation_mail_is_sent_upon_registration()
    {
        Mail::fake(); //TODO:faking email sent. Ускоряет тест, т.к. почта на самом деле не отправляется

        $user = factory(User::class)->create();
        event(new Registered($user));//TODO:faking email sent event

//        Mail::assertSent(); //какой класс отвечает за отправку письма по умолчанию?
    }

}
