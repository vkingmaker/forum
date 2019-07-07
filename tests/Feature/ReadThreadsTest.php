<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp():void
    {
        parent::setUp();

        $this->thread = create('App\Thread');
    }

    /** @test */
    public function a_user_can_view_all_threads()
    {

        $response = $this->get('/threads');

        $response->assertSee($this->thread->title);
    }

    /** @test */

    public function a_user_can_read_a_single_thread()
    {

        $response = $this->get($this->thread->path());

        $response->assertSee($this->thread->title);
    }

    /** @test */

   public function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $reply = create('App\Reply',['thread_id'=>$this->thread->id]);

        $this->get($this->thread->path())

            ->assertSee($reply->body);
    }
}
