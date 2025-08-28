<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Requests\TaskRequest;



//main
Route::get('/', action: function (){
    return redirect()->route('task.index');
});

// index page
Route::get('/tasks', action: function (){
//    $tasks = Task::FilterData('all', 5);
//    return view('index', ['tasks' => $tasks,'filter'=>'all']);
    $filter = request('filter', 'all');
    $limit = request('limit', 5);
    $tasks = Task::FilterData($filter, intval($limit));
    return view('index', ['tasks' => $tasks,'filter'=>$filter,'limit'=>$limit]);
})->name('task.index');

//filtered index page
// create page
Route::view('/tasks/create','todo-form.create-todo-item')->name('task.create');
// details page
Route::get('/tasks/{task}', action: function (Task $task)  {
    return view('list-items.task-details',['task'=>$task]);
})->name('task.details');
//creating new task
Route::post('/tasks', action: function (TaskRequest $request) {
    $task = Task::create($request->validated());
    return redirect()->route('task.details',['task'=>$task->id])->
        with('success','Task created successfully');

})->name('task.store');

//edit task
Route::get('/tasks/{task}/edit',function (Task $task){
    return view('todo-form.edit-todo',['task'=>$task]);
})->name('task.edit');
//update task
Route::put('/tasks/{task}',function (Task $task,TaskRequest $request){
    $task->update($request->validated());
    return redirect()->route('task.details',['task'=>$task->id])->
    with('success','Task updated successfully');
})->name('task.update');

//delete task
Route::delete('/tasks/{task}',function (Task $task){
    $task->delete();
    return redirect()->route('task.index')->
    with('success','Task deleted successfully');
})->name('task.delete');

Route::put('/tasks/{task}/complete',function (Task $task){
    $task->completed = !$task->completed;
    $task->save();
    return redirect()->route('task.details',['task'=>$task->id])->with(
        'success',"Task". ($task->completed?" completed":" marked as incomplete"),
    );
})->name('task.complete');;

//Route::fallback(action: function () {
//    return view('fallback');
//});
