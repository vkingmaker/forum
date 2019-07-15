<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;

class LockedThreadsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Thread $thread)
    {
            // $thread->lock();

            $thread->update(['locked' => true]);

    }


    public function destroy(Thread $thread)
    {
        // $thread->unlock();

        $thread->update(['locked' => false]);
    }


}
