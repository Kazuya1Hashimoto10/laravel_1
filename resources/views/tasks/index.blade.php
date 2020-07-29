@extends('layout')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col col-md-4">
        <nav class="panel panel-default">
          <div class="panel-heading">フォルダ</div>
          <div class="panel-body">
            <a href="{{ route('folders.edit', $current_folder_id) }}" class="btn btn-default btn-block">
              フォルダ名を変更する
            </a>
            <a href="{{ route('folders.create') }}" class="btn btn-default btn-block">
              フォルダを追加する
            </a>
            <form action="{{ route('folders.delete', $current_folder_id) }}" method='post'>
              @csrf
              @method('DELETE')
              <button type="submit" value="delete" class="btn btn-default btn-block">フォルダを削除する</button>
            </form>
          </div>
          <div class="list-group">
            @foreach($folders as $folder)
              <a
                  href="{{ route('tasks.index', [$folder->id]) }}"
                  class="list-group-item {{ $current_folder_id === $folder->id ? 'active' : '' }}"
              >
                {{ $folder->title }}
              </a>
            @endforeach
          </div>
        </nav>
      </div>
      <div class="column col-md-8">
        <div class="panel panel-default">
          <div class="panel-heading">タスク（{{ $situation }}）</div>
          <div class="panel-body">
            <div class="text-right">
              <a href="{{ route('tasks.create', ['folder' => $folder->id]) }}" class="btn btn-default btn-block">
                タスクを追加する
              </a>
            </div>
          </div>
          <table class="table">
            <thead>
            <tr>
              <th>タイトル</th>
              <th>状態</th>
              <th>期限</th>
              <th></th>
              <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($tasks as $task)
              <tr>
                <td>{{ $task->title }}</td>
                <td>
                  <span class="label {{ $task->status_class }}">{{ $task->status_label }}</span>
                </td>
                <td>{{ $task->formatted_due_date }}</td>
                <td><a href="{{ route('tasks.edit', ['folder' => $task->folder_id, 'task' => $task->id]) }}">編集</a></td>
                <td><form action="{{ route('tasks.delete', ['folder' => $task->folder_id, 'task' => $task->id]) }}" method='post'>
                  @csrf
                  @method('DELETE')
                <button type="submit" value="delete" class="btn btn-primary">削除</button>
                </form></td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
