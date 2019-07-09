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
