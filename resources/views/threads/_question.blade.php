 {{-- Editing the Thread --}}
<div class="card" v-if="editing">
    <div class="card-header">
        <div class="level">
                 <input name="title" type="text" class="form-control" v-model="form.title">
        </div>
    </div>

    <div class="card-body">
        <div class="form-group">
            <textarea name="body" class="form-control" rows="10" v-model="form.body"></textarea>
        </div>
    </div>

    <div class="card-footer">
        <div class="level">
        <button class="btn btn-xs border" @click="editing=true" v-show="!editing">Edit</button>
            <button class="btn btn-primary btn-xs border" @click="update">Update</button>
            <button class="btn btn-xs border ml-1" @click="cancel">Cancel</button>
            @can('update', $thread)
            <form action="{{ $thread->path()}}" method="POST" class="ml-auto">
                @csrf
                @method('DELETE')
                    <button type="submit" class="btn btn-link">Delete Thread</button>
            </form>
            @endcan
        </div>

    </div>
</div>

{{-- Viewing  the Thread --}}

<div class="card" v-else>
    <div class="card-header">
        <div class="level">

        <img src="{{asset($thread->creator->avatar_path)}}" alt="Thread creator name" width="35" height="25" class="mr-1">
            <span class="flex">
                <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a>
                posted: <span v-text="form.title"></span>
            </span>

        </div>
    </div>

    <div class="card-body" v-text="form.body"></div>

    <div class="card-footer"v-if="authorize('owns', thread)">
        <button class="btn btn-xs border" @click="editing = true">Edit</button>
    </div>
</div>
