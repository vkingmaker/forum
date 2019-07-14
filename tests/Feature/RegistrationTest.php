<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_confirmation_email_is_sent_upon_registration()
    {

        Mail::fake();

        $this->post(route('register'), [

            'name' => 'John',

            'email' => 'John@example.com',

            'password' => 'password',

            'password_confirmation' => 'password'

          ]);

        Mail::assertQueued(PleaseConfirmYourEmail::class);
    }

    /** @test */
    public function user_can_fully_verify_their_email_address()
    {
        Mail::fake();

       $this->post(route('register'), [

          'name' => 'John',

          'email' => 'John@example.com',

          'password' => 'password',

          'password_confirmation' => 'password'


        ]);

        $user =  User::whereName('John')->first();

        $this->assertFalse($user->confirmed);

        $this->assertNotNull($user->confirmation_token);

        // Let the user confirm their account

        $this->get(route('register.confirm', ['token' => $user->confirmation_token]))

                ->assertRedirect(route('threads'));

        tap($user->fresh(), function($user){

            $this->assertTrue($user->confirmed);

            $this->assertNull($user->confirmation_token);

        });




    }

    /** @test */
    public function configuring_an_invalid_token()
    {
        $this->withoutExceptionHandling();

        $this->get(route('register.confirm', ['token' => 'invalid']))

                ->assertRedirect(route('threads'))

                ->assertSessionHas('flash', 'Unknown token.');
    }
}
