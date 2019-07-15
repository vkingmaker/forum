<?php

namespace App;

use App\Favorite;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Cache\CacheManager;
use Carbon\Carbon;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

    /**
     * Don't auto-apply mass assignment protection
     *
     * @var array
     */

    protected $guarded = [];

    /**
     * The relations to eager load on every query
     *
     * @var array
     */

    protected $with = ['owner', 'favorites'];

    /**
     *  The accessors to append to the model's array from
     *
     * @var array
     */

    protected $appends = ['favoritesCount', 'isFavorited', 'isBest'];


    protected static function boot()
    {
        parent::boot();

        static::created(function($reply) {

            $reply->thread->increment('replies_count');

        });

        static::deleted(function($reply) {

            // if ($reply->isBest) {
            //     $reply->thread->update(['best_reply_id' => null]);
            // }

            $reply->thread->decrement('replies_count');

        });
    }

    /**
     *  A reply has an owner
     *
     * @return \Illuminate\Database\Eloquent\Relation\BelongsTo
     */

    public function owner()
    {
        return $this->belongsTo(User::class,'user_id');
    }


    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }


    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }


    public function mentionedUsers()
    {
        preg_match_all('/@([\w\-]+)/', $this->body, $matches);

        return $matches[1];

    }

    /**
     *  Determine the path to the reply.
     *
     * @return string
     */

    public function path()
    {
        return $this->thread->path()."#reply-{$this->id}";
    }


    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace('/@([\w\-]+)/', '<a href="/profiles/$1">$0</a>', $body);
    }


    public function isBest()
    {
        return $this->thread->best_reply_id == $this->id;
    }


    public function getIsBestAttribute()
    {
        return $this->isBest();
    }

}
