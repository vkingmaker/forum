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
