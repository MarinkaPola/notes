
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
        <form enctype="multipart/form-data" class="row g-3 needs-validation"  action="/notes" method="post">
            @csrf
            <div >
                <div class="col-md-16" style="padding-left: 0">
                    <label for="validationDefault01" class="form-label">Название</label>
                    <input type="text" class="form-control" required name="title" >
                </div>
                <div class="col-md-16">
                    <label for="exampleFormControlTextarea1" class="form-label">Текст</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1"  name="text" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Видимость записки</label>
                    <select class="form-control" id="exampleFormControlSelect1" required name="visibility" >
                        <option value="0">private</option>
                        <option value="1">public</option>
                    </select>
                </div>
                <input type="file" name="file" id="file" class="@error('title') is-invalid @enderror">
                <div style="margin-top: 10px; padding-left: 0" class="col-md-10">
                    <button class="btn btn-primary" type="submit">Отправить заметку</button>
                </div>
            </div>
        </form>

        <script>
            var simplemde = new SimpleMDE({ element: document.getElementById("exampleFormControlTextarea1") });
            console.log(document.getElementById("exampleFormControlTextarea1"));
        </script>
    </x-slot>
</x-app-layout>

