<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'confirmed' => true
    ];
});

$factory->state(App\User::class, 'unconfirmed', function() {

    return [
        'confirmed' => false
    ];

});

$factory->state(App\User::class, 'administrator', function() {

    return [
        'name' => 'vkingmaker'
    ];

});

$factory->define(App\Thread::class, function (Faker $faker){
    $title = $faker->sentence;
    return [
        'user_id'=> factory(User::class)->create()->id,
        'channel_id' => factory(App\Channel::class)->create()->id,
        'title' => $title,
        'body'=> $faker->paragraph,
        'visits' => 0,
        'slug' => str_slug($title),
        'locked' => false
    ];
});

$factory->define(App\Reply::class, function(Faker $faker) {
    return [
        'thread_id'=> factory(App\Thread::class)->create()->id,
        'user_id' => factory(App\User::class)->create()->id,
        'body' => $faker->paragraph
    ];
});

$factory->define(App\Channel::class, function(Faker $faker) {

    $name = $faker->word;
    return [
        'name' => $name,
        'slug' => $name
    ];
});

$factory->define(\Illuminate\Notifications\DatabaseNotification::class, function(Faker $faker) {
    return [
      'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),

      'type' => 'App\Notification\ThreadWasUpdated',

      'notifiable_id' => auth()->id() ?: factory('App\User')->create()->id,

      'notifiable_type' => 'App\User',

     'data' => ['foo' => 'bar']

    ];
});
