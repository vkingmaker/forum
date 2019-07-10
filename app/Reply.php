<?php

namespace App;

use App\Favorite;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Cache\CacheManager;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

    protected $guarded = [];

    protected $with = ['owner', 'favorites'];

    /**
     *  The accessors to append to the model's array from
     *
     * @var array
     */

    protected $appends = ['favoritesCount', 'isFavorited'];


    protected static function boot()
    {
        parent::boot();

        static::created(function($reply) {

            $reply->thread->increment('replies_count');

        });

        static::deleted(function($reply) {

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


    public function path()
    {
        return $this->thread->path()."#reply-{$this->id}";
    }

}
