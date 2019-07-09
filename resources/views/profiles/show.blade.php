@extends('layouts.app')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <div class="page-header">
            <h1>
                {{ $profileUser->name }}
            </h1>
        </div>
        @forelse ($activities as $date => $activity)
    <h3 class="card-header">{{ $date }}</h3>
        @foreach($activity as $record)
        @if (view()->exists("profiles.activities.{$record->type}"))
            @include ("profiles.activities.{$record->type}", ['activity'=> $record])
        @endif
        @endforeach
            &nbsp;
        @empty
            <p>There is no activity for this user yet</p>
        @endforelse
    </div>
</div>
    @endsection
