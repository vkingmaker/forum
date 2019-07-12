<?php

namespace App\Http\Controllers;

use App\Reply;
use App\User;
use App\Thread;
use App\Http\Requests\CreatePostRequest;
use App\Notifications\YouWereMentioned;

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
     * @params CreatePostForm $form
     * @return  \Illuminate\Database\Eloquent\Model|\Illuminate\Http\RedirectResponse
     */

    public function store($channelId, Thread $thread, CreatePostRequest $form)
    {
            $reply = $thread->addReply([

                'body' => request('body'),

                'user_id' => auth()->id()

            ]);

            preg_match_all('/\@([^\s\.]+)/', $reply->body, $matches);

            $names = $matches[1];

            foreach ($names as $name) {

                $user = User::whereName($name)->first();

                if($user) {

                    $user->notify(new YouWereMentioned($reply));
                }
            }

            return $reply->load('owner');
    }

    /**
     *  Update an existing reply
     *
     * @param Reply $reply
     */

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        try{

            $this->validate(request(), ['body' => 'required|spamfree']);

            $reply->update(request(['body']));

        } catch (\Exception $e) {

            return response('Sorry, your reply could not be saved at this time.', 422);

        }

    }

    /**
     *  Delete the given reply
     *
     * @param Reply $reply
     * @return \Illuminate\Http\RedirectResponse
     */


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
