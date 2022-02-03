<x-app-layout>
    <x-slot name="header">
        <span class="p-6 bg-white border-b border-gray-200" style="font-size:10px">
            You're logged in!
        </span>
        <a href="/notes/create" style="color: coral; float: right">Сreate a new note</a>
        <a href="/notes-shared-you" style="color: #4eff38; float: right; margin-right: 10px">Notes shared you</a>
    </x-slot>
    <div style="display: flex; justify-content: center; margin-top: 100px; ">
        <!-- Search form -->
        <form id="custom-search-form" class="form-search form-horizontal pull-right" action="/notes" method="get">
            <div class="input-append spancustom" >
                <input type="text" class="search-query" name="search" placeholder="Search" style="border: 1px solid">
                <button type="submit" class="btn btn-primary">search</button>
            </div>
        </form>
    </div>
   <x-notes>
   </x-notes>
</x-app-layout>


