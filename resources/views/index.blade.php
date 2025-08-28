@extends('layout.htmlDefault')

@section("classes","bg-gray-900 text-white min-h-screen flex flex-col justify-center items-center")
@section('content')

<div class="max-w-2xl bg-gray-800 rounded-xl shadow-2xl overflow-hidden p-6 md:p-8 transition-all duration-300 hover:shadow-purple-500/20">
    <h1 class="text-3xl md:text-4xl font-bold mb-4 text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">Todo List</h1>

    <div class="max-w-md mx-auto bg-gray-700/50 rounded-lg shadow-md p-6 flex flex-col gap-4  ">
        <!-- Add Todo Form -->
        <div class="flex justify-between  gap-2">

                <button
                    type="submit"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg w-fit hover:bg-blue-600 transition self-center text-2xl"
                >
                  <a href="{{route('task.create')}}">
                    Add
                  </a>
                </button>
            <div class="flex gap-2  text-center items-center bg-gray-600 rounded-lg  p-2 mr-2">
                <span class="h-fit ">
                Filter By:
                </span>
                <form action="{{route('task.index')}}" method="GET">

        <select name="filter"  class="bg-gray-600 cursor-pointer border-1 p-1 rounded-sm"  onchange="this.form.submit()">
            <option value="all"{{request('filter')==='all'?'selected':''}}> All</option>
            <option value="completed" {{request('filter')==='completed'?'selected':''}}> Completed</option>
            <option value="pending" {{request('filter')==='pending'?'selected':''}}> Pending</option>
            <option value="oldest" {{request('filter')==='oldest'?'selected':''}}> Oldest</option>
            <option value="latest" {{request('filter')==='latest'?'selected':''}}> Latest</option>
        </select>
                        Items
                    <select class="bg-gray-600 cursor-pointer border-1 p-1 rounded-sm" name="limit" onchange="this.form.submit()">
                        <option {{request('limit')=='5'?'selected':''}} value=5>5</option>
                        <option {{request('limit')=='10'?'selected':''}} value=10>10</option>
                        <option {{request('limit')=='15'?'selected':''}} value=15>15</option>
                        <option {{request('limit')=='20'?'selected':''}} value=20>20</option>
                    </select>
                </form>
            </div>
        </div>


        <!-- Todo List -->
        <div class="space-y-2 min-w-[400px] flex flex-col gap-2">
            <!-- Todo Item Example -->
            @forelse($tasks as $task)
                        @include('list-items.list-item',[$task])
            @empty
                    <div>No Tag</div>
            @endforelse



            <!-- Todo Item Example -->

        </div>
    </div>
    <div class="mt-5">
        @if($tasks->count())
            {{$tasks->appends(request()->query())->links()}}
        @endif
    </div>
</div>
@endsection
