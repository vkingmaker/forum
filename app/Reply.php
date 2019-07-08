<?php

namespace App;

use App\Favorite;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Cache\CacheManager;

class Reply extends Model
{
    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo(User::class,'user_id');
    }


    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }


    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];
        if(! $this->favorites()->where($attributes)->exists()) {

            $this->favorites()->create($attributes);
        }
    }
}
