<x-app-layout>
    <x-slot name="header">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
<form enctype="multipart/form-data" class="row g-3 needs-validation"  action="/notes/{{$note->uuid}}" method="post">
    @csrf
    @method('put')
    <div >
        <div class="col-md-16" style="padding-left: 0">
            <label for="validationDefault01" class="form-label">Название</label>
            <input type="text" class="form-control" required name="title" value="{{$note->title}}">
        </div>
        <div class="col-md-16">
            <label for="exampleFormControlTextarea1" class="form-label">Текст</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" required name="text" rows="3">{{$note->text}}</textarea>
        </div>
        <div class="form-group">
            <label for="exampleFormControlSelect1">Видимость записки</label>
            <select class="form-control" id="exampleFormControlSelect1" required name="visibility">
                <option  value="{{$note->visibility}}">
                    @if($note->visibility===0)
                      private
                    @endif
                        @if($note->visibility===1)
                            public
                        @endif
                </option>
                @if($note->visibility===0)
                <option value="1">public</option>
                @endif
                @if($note->visibility===1)
                <option value="0">private</option>
                @endif
            </select>
        </div>
        @foreach($note->attachments as $attachment)
                <p>
                    <label for="file_name">Delete file</label>
                        <input id="file_name" type="checkbox" name="delete_attachments[]" value="{{$attachment->id}}">
                        {{$attachment->file_original_name}}
                </p>
        @endforeach
        <input type="file" name="file">
        <div style="margin-top: 10px; padding-left: 0" class="col-md-10">
            <button class="btn btn-primary" type="submit">UPDATE</button>
        </div>
    </div>
</form>
<script>
    var simplemde = new SimpleMDE({ element: document.getElementById("exampleFormControlTextarea1") });
    console.log(document.getElementById("exampleFormControlTextarea1"));
</script>
    </x-slot>
</x-app-layout>
