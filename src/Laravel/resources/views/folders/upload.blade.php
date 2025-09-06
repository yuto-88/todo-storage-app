@extends('layout')

@section('content')
<div class="container">
    <h2>ファイルアップロード</h2>

    {{-- 成功メッセージ --}}
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- バリデーションエラー表示 --}}
    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form id="upload-form" action="{{ route('tasks.upload', ['id' => $folder_id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div id="drop-area" style="border: 2px dashed #ccc; padding: 30px; text-align: center; margin-top: 30px;">
            <p id="drag-text-1" style="margin-bottom: 10px;">ここにファイルをドラッグ＆ドロップ</p>
            <p id="drag-text-2" style="margin-bottom: 10px;">または</p>
            <label id="file-select-label" class="btn btn-primary">
                ファイルを選択
                <input id="file-input" type="file" name="file" style="display: none;" accept=".mcworld">
            </label>
        </div>

        {{-- 確認用ボックス --}}
        <div id="confirm-box" style="margin-top: 20px; display: none; text-align: center;">
            <p id="file-name">ファイル名：</p>
            <p>このファイルをアップロードしますか？</p>
            <button type="submit" class="btn btn-success">はい</button>
            <button type="button" class="btn btn-secondary" onclick="cancelUpload()">いいえ</button>
        </div>
    </form>

    <a href="{{ route('tasks.index', ['id' => $folder_id]) }}" class="btn btn-link mt-3">← タスク一覧に戻る</a>
</div>

<script>
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('file-input');
    const confirmBox = document.getElementById('confirm-box');
    const fileNameText = document.getElementById('file-name');
    const fileSelectLabel = document.getElementById('file-select-label');
    const dragText1 = document.getElementById('drag-text-1');
    const dragText2 = document.getElementById('drag-text-2');

    // ドラッグ＆ドロップエリア操作
    dropArea.addEventListener('dragover', e => {
        e.preventDefault();
        dropArea.style.background = '#eef';
    });

    dropArea.addEventListener('dragleave', () => {
        dropArea.style.background = '';
    });

    dropArea.addEventListener('drop', e => {
        e.preventDefault();
        dropArea.style.background = '';

        if (e.dataTransfer.files.length > 0) {
            const file = e.dataTransfer.files[0];
            if (validateFile(file)) {
                fileInput.files = e.dataTransfer.files;
                showConfirmation(file);
            }
        }
    });

    // ファイル選択
    fileInput.addEventListener('change', function () {
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            if (validateFile(file)) {
                showConfirmation(file);
            }
        }
    });

    // 拡張子確認
    function validateFile(file) {
        if (!file.name.endsWith('.mcworld')) {
            alert('拡張子が .mcworld のファイルのみアップロード可能です。');
            cancelUpload();
            return false;
        }
        return true;
    }

    // 確認ボックス表示、選択UIをすべて隠す
    function showConfirmation(file) {
        fileNameText.textContent = 'ファイル名：' + file.name;
        fileSelectLabel.style.display = 'none';  // ボタン非表示
        dragText1.style.display = 'none';        // 「ドラッグ＆ドロップ」テキスト非表示
        dragText2.style.display = 'none';        // 「または」テキスト非表示
        dropArea.style.border = 'none';          // 枠線も消す（お好みで）
        confirmBox.style.display = 'block';      // 確認表示
    }

    // リセット（リロード）
    function cancelUpload() {
        location.reload(); // ページをリロードしてすべて初期化
    }
</script>
@endsection
