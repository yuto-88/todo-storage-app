<!--
*   extends：親ビューを継承する（読み込む）
*   親ビュー名：layout を指定
-->
@extends('layout')

<!--
*   section：子ビューにsectionでデータを定義する
*   セクション名：scripts を指定
*   用途：javascriptライブラリー「flatpickr」のスタイルシートを指定
-->
@section('styles')
<!-- 「flatpickr」の デフォルトスタイルシートをインポート -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- 「flatpickr」の ブルーテーマの追加スタイルシートをインポート -->
<link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">
@endsection

<!--
*   section：子ビューにsectionでデータを定義する
*   セクション名：content を指定
*   用途：タスクを追加するページのHTMLを表示する
-->
@section('content')
<div class="container">
    <div class="row">
        <div class="col col-md-4">
            <nav class="panel panel-default">
                <div class="panel-heading">フォルダ</div>
                <div class="panel-body">
                    <a href="{{ route('folders.create') }}" class="btn btn-default btn-block">
                        フォルダを追加する
                    </a>
                </div>
                <div class="list-group">
                    <table class="table foler-table">
                        @foreach($folders as $folder)
                        @if($folder->user_id === Auth::user()->id)
                        <tr>
                            <td>
                                <a href="{{ route('tasks.index', ['id' => $folder->id]) }}" class="list-group-item {{ $folder_id === $folder->id ? 'active' : '' }}">
                                    {{ $folder->title }}
                                </a>
                            </td>
                            <td><a href="{{ route('folders.edit', ['id' => $folder->id]) }}">編集</a></td>
                            <td><a href="{{ route('folders.delete', ['id' => $folder->id]) }}">削除</a></td>
                            <td><a href="{{ route('tasks.upload.form', ['id' => $folder->id]) }}" class="btn btn-primary">
                                UP
                                </a></td>
                        </tr>
                        @endif
                        @endforeach
                    </table>
                </div>
            </nav>
        </div>
        <div class="column col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">タスク</div>
                <div class="panel-body">
                    <div class="text-right">
                        <a href="{{ route('tasks.create', ['id' => $folder_id]) }}" class="btn btn-default btn-block">
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
                        <td>{{ $task->due_date }}</td>
                        <td><a href="{{ route('tasks.edit', ['id' => $task->folder_id, 'task_id' => $task->id]) }}">編集</a></td>
                        <td><a href="{{ route('tasks.delete', ['id' => $task->folder_id, 'task_id' => $task->id]) }}">削除</a></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                {{-- ▼ アップロード済みファイル表示ボックス --}}
                <div class="panel panel-info" style="margin-top: 30px;">
                    <div class="panel-heading">アップロードされたMinecraftワールド一覧</div>
                    <div class="panel-body">
                        @if($uploadedFiles->isEmpty())
                        <p>アップロードされたファイルはまだありません。</p>
                        @else
                        <ul class="list-group">
                            @foreach($uploadedFiles as $file)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    {{-- ファイル名 --}}
                                    <strong>{{ $file->original_name ?? basename($file->file_path) }}</strong><br>

                                    {{-- URL --}}
                                    <a href="{{ Storage::url($file->file_path) }}" target="_blank">
                                        {{ Storage::url($file->file_path) }}
                                    </a><br>

                                    {{-- アップロードユーザー --}}
                                    <small>アップロード者: {{ $file->folder->user->name ?? '不明' }}</small><br>

                                    {{-- アップロード日時 --}}
                                    <small>アップロード日時: {{ $file->created_at->format('Y年m月d日 H:i') }}</small>
                                </div>

                                {{-- 削除 --}}
                                <form action="{{ route('tasks.upload.delete', ['id' => $folder_id]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                                    @csrf
                                    <input type="hidden" name="filename" value="{{ basename($file->file_path) }}">
                                    <button type="submit" class="btn btn-danger btn-sm">削除</button>
                                </form>
                            </li>
                            @endforeach

                        </ul>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection


