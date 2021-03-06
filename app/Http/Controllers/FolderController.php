<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Folder;
use App\Http\Requests\CreateFolder;
use App\Http\Requests\EditFolder;
use Illuminate\Support\Facades\Auth;
use App\Task;

class FolderController extends Controller
{
    public function showCreateForm()
    {
        return view('folders/create');
    }

    public function create(CreateFolder $request)
    {
    // フォルダモデルのインスタンスを作成する
    $folder = new Folder();
    // タイトルに入力値を代入する
    $folder->title = $request->title;
    // インスタンスの状態をデータベースに書き込む
    // ★ ユーザーに紐づけて保存
    Auth::user()->folders()->save($folder);

    return redirect()->route('tasks.index', 
        ['folder' => $folder->id,]);
    }

    public function showEditForm(Folder $folder){
        return view('folders/edit', [
            'folder' => $folder,
        ]);
    }

    public function edit(Folder $folder, EditFolder $request){
        $folder->title = $request->title;
        $folder->save();

        return redirect()->route('tasks.index',[
            'folder' => $folder->id,
        ]);
    }

    public function destroy(Folder $folder)
    {
        $tasks = \App\Task::where('folder_id',$folder->id);
        $tasks->delete();
        $folder->delete();

        return redirect('/');
    }

}
