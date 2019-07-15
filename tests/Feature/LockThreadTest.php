<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LockThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function non_administrators_may_not_lock_threads()
    {
      $this->signIn();

      $thread = create('App\Thread', ['user_id' => auth()->id()]);

      $this->post(route('locked-threads.store', $thread))->assertStatus(403);

      $this->assertFalse(!! $thread->fresh()->locked);
    }

    /** @test */
    public function administrator_can_lock_threads()
    {
        $this->withoutExceptionHandling();

        $this->signIn(factory('App\User')->states('administrator')->create());

        $thread = create('App\Thread', ['user_id' => auth()->id()]);


        $this->post(route('locked-threads.store', $thread));

        $this->assertTrue(!! $thread->fresh()->locked);

    }

    /** @test */
    public function once_locked_a_thread_may_not_recieve_new_replies()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $thread = create('App\Thread');

        $thread->lock();

        $this->post($thread->path().'/replies',[
            'body' => 'Foobar',
            'user_id' => create('App\User')->id
        ])->assertStatus(422);
    }
}
