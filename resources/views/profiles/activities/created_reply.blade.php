@component('profiles.activities.activity')
    @slot('heading')
    {{ $profileUser->name }} replied to
    <a href="{{ $activity->subject->thread->path()}}">{{ $activity->subject->thread->title }}</a>
    @endslot
    @slot('body')
    {{ $activity->subject->body }}
    @endslot
@endcomponent

@include('profiles.activities.activity', [
    'heading' => 'my heading',
    'body' => 'my body'
])


