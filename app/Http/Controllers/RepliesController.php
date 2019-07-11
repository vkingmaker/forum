<?php

namespace App\Http\Controllers;

use App\Inspections\Spam;

use App\Reply;
use App\Thread;

class RepliesController extends Controller
{
    /**
     *  Create a new RepliesController instance.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    /**
     *  Persist a new reply
     *
     * @param integer $channelId
     * @param Thread $thread
     * @param Spam Spam
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store($channelId, Thread $thread, Spam $spam)
    {
        $this->validate(request(), [
            'body' => 'required'
        ]);

        $spam->detect(request('body'));

        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);

        if(request()->expectsJson()) {
            return $reply->load('owner');
        }


        return back()->with('flash', "Your reply has been left.");
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->update([
            'body' => request('body')
        ]);
    }


    public function destroy(Reply $reply)
    {

        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }

        return back();
    }
}
