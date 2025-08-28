
<div class="flex items-center justify-between p-3 bg-gray-700/50 rounded-lg shadow-md transition hover:bg-gray-800/50">
    <a href="{{route('task.details', ['task'=>$task->id])}}" class="text-white hover:underline truncate">

        <span class="flex-1 line-through text-gray-300">{{$task->title}}</span>
    </a>
    <div class="flex gap-2">
        <button class="text-green-500 hover:text-green-700">
            ✓
        </button>
        <button class="text-red-500 hover:text-red-700">
            ×
        </button>
    </div>
</div>

