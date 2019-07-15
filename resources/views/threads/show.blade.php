@extends('layouts.app')

@section('header')
<link rel="stylesheet" href="{{ asset('css/vendor/jquery.atwho.css') }}">
@endsection

@section('content')
<thread-view :thread="{{ $thread }}" inline-template>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="level">

                    <img src="{{asset($thread->creator->avatar_path)}}" alt="Thread creator name" width="35" height="25" class="mr-1">
                        <span class="flex">
                            <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a>
                            posted:
                            {{ $thread->title }}
                        </span>
                    @can('update', $thread)
                    <form action="{{ $thread->path()}}" method="POST">
                        @csrf
                        @method('DELETE')
                            <button type="submit" class="btn btn-link">Delete Thread</button>
                    </form>
                    @endcan
                    </div>
                </div>

                <div class="card-body">
                    {{ $thread->body }}
                </div>
            </div>

        <replies @added="repliesCount++" @removed="repliesCount--"></replies>



        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <p>
                        This thread was published {{ $thread->created_at->diffForHumans() }}
                    by <a href="{{ route('profile',$thread->creator) }}">{{ $thread->creator->name }}</a>, and currently has <span v-text="repliesCount"></span>
                        {{ str_plural('comment', $thread->replies_count )}}. comments.
                    </p>
                    <p>
                    <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}" v-if="signedIn"></subscribe-button>

                    <button class="btn btn-default border"v-if="authorize('isAdmin')" @click="toggleLock" v-text="locked ? 'Unlock' : 'Lock'"></button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</thread-view>
@endsection
