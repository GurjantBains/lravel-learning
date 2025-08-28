@extends('layout.htmlDefault')
@section("classes","bg-gray-900 text-white min-h-screen flex justify-center items-center")
@section('content')
    <div class="max-w-2xl w-full bg-gray-800 rounded-xl shadow-2xl overflow-hidden p-6 md:p-8 transition-all duration-300 hover:shadow-purple-500/20">
        <h1 class="text-3xl md:text-4xl font-bold mb-6 text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">
            Edit Task
        </h1>

        <form method="POST" action="{{ route('task.update', ['task' => $task->id]) }}" class="space-y-5">
            @method('PUT')
            @csrf

            <div class="flex flex-col gap-2">
                <label for="title" class="text-sm font-medium text-gray-200">Title</label>
                <input
                    id="title"
                    type="text"
                    name="title"
                    placeholder="Title"
                    value="{{ $task->title }}"
                    class="w-full rounded-lg bg-gray-900/60 border border-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 px-4 py-2.5 text-gray-100 placeholder-gray-400 outline-none transition"
                >
                @error('title')
                <div class="text-red-400 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex flex-col gap-2">
                <label for="description" class="text-sm font-medium text-gray-200">Description</label>
                <input
                    id="description"
                    type="text"
                    name="description"
                    placeholder="Description"
                    value="{{ $task->description }}"
                    class="w-full rounded-lg bg-gray-900/60 border border-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 px-4 py-2.5 text-gray-100 placeholder-gray-400 outline-none transition"
                >
                @error('description')
                <div class="text-red-400 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex flex-col gap-2">
                <label for="long_description" class="text-sm font-medium text-gray-200">Due Date</label>
                <input
                    id="long_description"
                    type="text"
                    name="long_description"
                    placeholder="Long Description"
                    value="{{ $task->long_description }}"
                    class="w-full rounded-lg bg-gray-900/60 border border-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 px-4 py-2.5 text-gray-100 placeholder-gray-400 outline-none transition"
                >
                @error('long_description')
                <div class="text-red-400 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button
                    type="submit"
                    class="cursor-pointer bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white text-lg px-5 py-2.5 rounded-lg transition shadow-md"
                >
                    Update
                </button>
                <a
                    href="{{ route('task.details',['task'=>$task->id]) }}"
                    class="text-gray-300 hover:text-white transition underline-offset-4 hover:underline"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
