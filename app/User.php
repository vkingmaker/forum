<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

        'confirmed' => 'boolean',
    ];

      /**
     *  Get the route key name for Laravel
     *
     * @return string
     */

    public function getRouteKeyName()
    {
        return 'name';
    }

    /**
     *  Fetch all threads that were created by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function threads()
    {
        return $this->hasMany(Thread::class)->latest();
    }


    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    /**
     *  Get all Activity for user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }


    public function confirm()
    {
        $this->confirmed = true;

        $this->save();
    }

    public function getAvatarPathAttribute($avatar)
    {

        return asset($avatar ? 'storage/'.$avatar : 'storage/avatars/default.jpg');

    }

    public function visitedThreadCachedKey($thread)
    {
        return sprintf("users.%s.visits.%s", $this->id, $thread->id);
    }

    public function read($thread)
    {
         // Simulate that the user did read the Thread

         cache()->forever($this->visitedThreadCachedKey($thread),

         \Carbon\Carbon::now());
    }
}
