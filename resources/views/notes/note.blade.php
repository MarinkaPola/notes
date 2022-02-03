
<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        @if(session()->has('success'))
            <p class="alert alert-success">{{session()->get('success')}}</p>
        @endif
            @if(session()->has('warning'))
                <p class="alert alert-danger">{{session()->get('warning')}}</p>
            @endif
            @if($errors->any())
            <div class = "alert alert-error">
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
                </ul>
            </div>
            @endif
        <div class="card text-dark bg-light border-primary mb-3" style="width: 50%; position: inherit">
            <div class="card-body">
            <h2 class="card-title" style="color: #ff8dac">{{$note->title}}</h2>
                @markdown
                {!!$note->text!!}
                @endmarkdown
            @if($note->visibility===1)
                <p>public</p>
            @endif
            @if($note->visibility===0)
                <p>private</p>
            @endif
            @foreach($note->attachments as $attachment)
                <div style="display: flex; flex-direction: row">
                    <p>{{$attachment->file_original_name}}</p>
                    @if($note->user_id===Auth::id() && $note->attachments()->exists())
                        <form action="/notes/{{$note->uuid}}/detach" method="post">
                            @csrf
                            @method('patch')
                            <p>
                                <button class="btn btn-info" type="submit" value="{{$attachment->id}}"
                                        name="id" style="margin-left: 10px">Detach
                                </button>
                            </p>
                        </form>
                    @endif
                    @if(($note->user_id===Auth::id() || $note->visibility === 1 || $note->sharedWith(Auth::user())) &&
                    ($note->attachments()->exists()))
                        <form action="/notes/{{$note->uuid}}/download" method="get">
                            @csrf
                            <p>
                                <button class="btn btn-info" type="submit" value="{{$attachment->id}}"
                                        name="id" style="margin-left: 10px">Download
                                </button>
                            </p>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
            @if($note->user)
            <div class="card-footer">{{$note->user->name}}</div>
            @endif
        </div>
        @if($note->user_id===Auth::id())
            <form action="/notes/{{$note->uuid}}/edit" method="get">
                <button type="submit" class="btn btn-warning" style="margin-bottom: 10px">EDIT</button>
            </form>
            <form action="/notes/{{$note->uuid}}" method="post">
                @csrf
                @method('delete')
                <button type="submit" class="btn btn-danger">DELETE</button>
            </form>
            <form action="/notes/{{$note->uuid}}/share" method="post">
                @csrf
                @method('post')
                <label class="form-label">Введите email</label>
                <input type="email" name="email">
                <button type="submit" class="btn btn-success" style="margin-left: 6px">SHARED</button>
            </form>
        @endif
    </x-slot>
</x-app-layout>


