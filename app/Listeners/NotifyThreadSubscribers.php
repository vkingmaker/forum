<?php

namespace App\Listeners;

use App\Events\ThreadHasNewReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyThreadSubscribers
{

    /**
     * Handle the event.
     *
     * @param  ThreadHasNewReply  $event
     * @return void
     */
    public function handle(ThreadHasNewReply $event)
    {
        // Prepare notifications for all subscribers.

        $event->thread->subscriptions

                ->where('user_id', '!=', $event->reply->user_id)

                ->each

                ->notify($event->reply);

        // $this->subscriptions->filter(function($sub) use ($reply) {

        //     return $sub->user_id != $event->reply->user_id;

        // })->each->notify($event->reply);
    }
}
