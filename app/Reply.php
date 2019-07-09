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



}
