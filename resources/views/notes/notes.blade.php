<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
      integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<body>
<div style="display: flex; flex-direction: column; ">
    <div class="container" style="margin-left: 20px;">
        @if($notes->count())
            @foreach($notes as $note)
                <div class="card text-dark bg-light border-primary mb-3" style="width: 30%; position: inherit">
                    <div class="card-body">
                        <article style="margin: 15px">
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
                            <button type="button" class="btn btn-info">
                                @if(Auth::guest())
                                    <a href="/notes/{{$note->uuid}}/public">details</a>
                                @endif
                                @if(!Auth::guest())
                                    <a style="color:white" href="/notes/{{$note->uuid}}">details</a>
                                @endif
                            </button>
                        </article>
                    </div>
                    @if($note->user)
                    <div class="card-footer">{{$note->user->name}}</div>
                        @endif
                </div>
            @endforeach
        @endif
    </div>
    {{ $notes->onEachSide(2)->links()}}
</div>
</body>
