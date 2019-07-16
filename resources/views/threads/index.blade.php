@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            @include('threads._list')

            {{$threads->render() }}
        </div>
        @if (count($trending))
        <div class="col-md-4">
                <div class="card">
                    <div class="card-heading px-3 pt-2">
                        Search
                    </div>
                    <div class="card-body">
                        <form method="GET" action="/threads/search">
                            <div class="form-group">
                            <input type="text" placeholder="Searching for something..." name="q" class="form-control">
                            </div>

                            <div class="form-group">
                                <button class="btn btn-default border" type="search">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif


        @if (count($trending))
        <div class="col-md-4">
                <div class="card">
                    <div class="card-heading px-3 pt-2">
                        Trending thread
                    </div>
                    <div class="card-body">
                    <ul class="list-group">
                     @foreach ($trending as $thread)
                            <li class="list-group-item">
                                <a href="{{ url($thread->path)}}">
                                    {{ $thread->title }}
                                </a>
                            </li>
                     @endforeach
                    </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
