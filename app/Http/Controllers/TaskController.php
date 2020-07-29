<?php

namespace App\Http\Controllers;

use App\Folder;
use App\Task;
use App\Http\Requests\CreateTask;
use App\Http\Requests\EditTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Folder $folder)
    {
    // ユーザーのフォルダを取得する
    $folders = Auth::user()->folders()->get();

    // 選ばれたフォルダに紐づくタスクを取得する
    $tasks = $folder->tasks()->get();

    // タスクの達成状況を出す
    $progress_num = 0;
    $count = 0;
    foreach ($tasks as $task){
        $count += 1;
         switch ($task->status){
            case '1';
                $progress_num += 1;
                break;
            case '2';
                $progress_num += 2;
                break;
            case '3';
                $progress_num += 3;
                break;
         }
    }

    if($count != 0){
        $progress = $progress_num / $count;
    
        if($progress == 1){
            $situation = "進捗率 0%";
        }elseif($progress < 1.5){
            $situation = "進捗率 20%";
        }elseif($progress < 2){
            $situation = "進捗率 40%";
        }elseif($progress < 2.5){
            $situation = "進捗率 60%";
        }elseif($progress < 3){
            $situation = "進捗率 80%";
        }elseif($progress == 3){
            $situation = "進捗率 100%";
        }
    }else{
        $situation = "タスクなし";
    }


    return view('tasks/index', [
        'folders' => $folders,
        'current_folder_id' => $folder->id,
        'tasks' => $tasks,
        'situation' => $situation,
    ]);
    }
    
    /**
     * GET /folders/{id}/tasks/create
     */
    public function showCreateForm(Folder $folder)
    {
    return view('tasks/create', [
        'folder_id' => $folder->id,
    ]);
    }

    public function create(Folder $folder, CreateTask $request)
    {

    $task = new Task();
    $task->title = $request->title;
    $task->due_date = $request->due_date;

    $folder->tasks()->save($task);

    return redirect()->route('tasks.index', [
        'folder' => $folder->id,
    ]);
    }

    public function showEditForm(Folder $folder,Task $task)
    {
    return view('tasks/edit', [
        'task' => $task,
    ]);
    }

    public function edit(Folder $folder, Task $task, EditTask $request)
    {
    $task->title = $request->title;
    $task->status = $request->status;
    $task->due_date = $request->due_date;
    $task->save();

    return redirect()->route('tasks.index', [
        'folder' => $task->folder_id,
    ]);
    }

    public function destroy(Folder $folder, Task $task)
    {
    $task->delete();

    return redirect('/');
    }

    private function checkRelation(Folder $folder, Task $task)
    {
    if ($folder->id !== $task->folder_id) {
        abort(404);
    }
    }
}
