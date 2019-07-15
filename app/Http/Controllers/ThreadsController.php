<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Channel;
use Illuminate\Http\Request;
use App\Filters\ThreadFilters;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use App\Trending;
use App\Rules\Recaptcha;

class ThreadsController extends Controller
{
    /**
     *  Create a new ThreadsController instance
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     *
     * #param Channel $channel
     * @param ThreadFilters $filters
     * @return \Illuminate\Http\Response
     */

    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {
        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) {

            return $threads;

        }

        return view('threads.index', [

            'threads' => $threads,

            'trending' => $trending->get()

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Recaptcha $recaptcha)
    {

        $this->validate($request,[

            'title' => 'required|spamfree',

            'body' => 'required|spamfree',

            'channel_id' => 'required|exists:channels,id',

            'g-recaptcha-response' => ['required', $recaptcha]

        ]);

       $thread = Thread::create([

            'user_id' => auth()->id(),

            'channel_id' => request('channel_id'),

            'title' => request('title'),

            'body' => request('body')

        ]);

        if(request()->wantsJson()) {

            return response($thread, 201);

        }

        return redirect($thread->path())

            ->with('flash', "Your thread has been published");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */

    public function show($channelId, Thread $thread, Trending $trending)
    {
        if (auth()->check()) {

            auth()->user()->read($thread);

        }

        $trending->push($thread);

        $thread->increment('visits');

        return view('threads.show', compact('thread'));
    }


    public function update($channel, Thread $thread)
    {
        $this->authorize('update', $thread);

       $data = request()->validate([

            'title' => 'required|spamfree',

            'body' => 'required|spamfree',

        ]);

        $thread->update($data);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {

        $this->authorize('update', $thread);

        $thread->delete();

        if(request()->wantsJson()) {

            return response([], 204);

        }

        return redirect('/threads');

    }


    public function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest()->filter($filters);

        if($channel->exists) {

            $threads->where('channel_id', $channel->id);

        }

        return $threads->paginate(25);
    }
}
