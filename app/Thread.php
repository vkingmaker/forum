<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    /**
     * Don't auto-apply mass assignment  protection
     *
     * @var array
     */

 protected $guarded = [];

 protected $with = ['creator', 'channel'];


    protected static function boot()
    {
        parent::boot();


        static::deleting(function($thread) {

            $thread->replies->each->delete();

        });
    }

 public function path()
 {
     return "/threads/{$this->channel->slug}/{$this->id}";
 }

 public function replies()
 {
     return $this->hasMany(Reply::class);
 }

 public function creator()
 {
     return $this->belongsTo(User::class, 'user_id');
 }

 /**
  *  Add a reply to the thread
  * @param array $reply
  * @return Model
  */

 public function addReply($reply)
 {
   return $this->replies()->create($reply);
 }

 /**
  *  Apply all relevant thread filters
  * @param Builder $query
  * @return Builder
  */

 public function channel()
 {
     return $this->belongsTo(Channel::class);
 }


 public function scopeFilter($query, $filters)
 {
    return $filters->apply($query);
 }


 public function subscribe($userId = null)
 {
    $this->subscriptions()->create([
        'user_id' => $userId ?: auth()->id()
    ]);
 }


 public function unsubscribe($userId = null)
 {
    $this->subscriptions()->where('user_id', $userId ?: auth()->id())->delete();
 }

 public function subscriptions()
 {
    return $this->hasMany(ThreadSubscription::class);
 }
}
