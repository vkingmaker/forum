<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AddAvatarTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    public function only_members_can_add_avatar()
    {
        $this->withExceptionHandling();

        $this->json('POST', 'api/users/1/avatar')

              ->assertStatus(401);
    }

    /** @test */
    public function a_valid_avatar_must_be_provided()
    {
        $this->withExceptionHandling()->signIn();

        $this->json('POST', 'api/users/'. auth()->id().'/avatar', [
            'avatar' => 'not-an-image'
        ])->assertStatus(422);
    }

    /** @test */
    public function a_user_may_add_an_avatar_to_their_profile()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $this->json('POST', 'api/users/'. auth()->id().'/avatar', [

            'avatar' => $file

        ]);

        $this->assertEquals('avatars/'.$file->hashName(), auth()->user()->avatar_path);

        Storage::disk('public')->assertExists('avatars/'. $file->hashName());
    }

    /** @test */
    public function the_user_can_determine_their_avatar_path()
    {
        $user = create('App\User');

        $this->assertEquals('avatars/default.jpg', $user->avatar());

        $user->avatar_path = 'avatars/me.jpg';

        $this->assertEquals('avatars/me.jpg', $user->avatar());
    }
}
