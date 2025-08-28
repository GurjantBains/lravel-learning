@extends('layout.htmlDefault')

@section("classes","bg-gradient-to-br from-gray-900 to-gray-800 text-gray-100 min-h-screen p-4 md:p-8 flex justify-center items-center")
@section('content')
    <div class="max-w-2xl bg-gray-800 rounded-xl shadow-2xl overflow-hidden p-6 md:p-8 transition-all duration-300 hover:shadow-purple-500/20">
        <h1 class="text-3xl md:text-4xl font-bold mb-4 text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">{{ $task->title }}</h1>

        <div class="mb-6">
            <p class="text-gray-300"><strong class="text-gray-200">Description:</strong> {{ $task->description }}</p>
            @if($task->long_description)
                <p class="mt-4 text-gray-300"><strong class="text-gray-200">Long Description:</strong> {{ $task->long_description }}</p>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <form class="" method="post" action="{{route('task.complete',['task'=>$task->id])}}">
            <div class="bg-gray-700/50 rounded-lg p-4">
                <p class="text-gray-300"><strong class="text-gray-200">Status:</strong>
                    @csrf
                    @method('PUT')

                    <button class="complete-button ml-1 cursor-pointer px-3 py-1 rounded-full text-sm font-semibold {{ $task->completed ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400' }}">
                        {{ $task->completed ? 'Completed' : 'Pending' }}
                    </button>
                </p>
            </div>
                </form>
            <div class="bg-gray-700/50  rounded-lg p-4">
                <p class="text-gray-300"><strong class="text-gray-200">Created At:</strong> {{ $task->created_at->diffForHumans() }}</p>
                <p class="text-gray-300"><strong class="text-gray-200">Updated At:</strong> {{ $task->updated_at->diffForHumans() }}</p>
            </div>
        </div>

        <div class="bg-gray-700/50 rounded-lg p-4">
            <p class="text-gray-300"><strong class="text-gray-200">Updated At:</strong> {{ $task->updated_at   }}</p>
        </div>
<div class="flex justify-center mt-4 gap-3">
    <a href="{{ route('task.edit', $task->id) }}" class="bg-green-500 text-white px-4 py-2 rounded mr-2 hover:bg-green-600 transition duration-300">Edit Task</a>
    <a href="{{ route('task.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-300 ">Back to Task List</a>
    <form onsubmit='return confirm("Delete Task")' action="{{route('task.delete',['task'=>$task->id])}}" class="self-end" method="POST">
        @csrf
        @method('DELETE')
        <button  class="bg-red-500 text-white px-4 py-2 rounded mr-2 hover:bg-red-600 transition duration-300 cursor-pointer">
            Delete
        </button>
    </form>
</div>

@endsection

