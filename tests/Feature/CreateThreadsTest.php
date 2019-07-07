<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    public function guest_may_not_create_threads()
    {
        $this->withoutExceptionHandling()

         ->expectException('Illuminate\Auth\AuthenticationException');

        $thread = make('App\Thread');

        $this->post('/threads', $thread->toArray());
    }

    /** @test */
    public function guest_can_not_see_the_create_page()
    {
        $this->get('/threads/create')

            ->assertRedirect('/login');
    }

   /** @test */
   public function an_authenticated_user_can_create_a_new_forum_threads()
   {
    $this->signIn();

    $thread = make('App\Thread');

    $this->post('/threads', $thread->toArray());

    $this->get($thread->path())->assertSee($thread->title)

        ->assertSee($thread->body);

   }
}
