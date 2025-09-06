<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\FolderFile;
use Illuminate\Http\UploadedFile;

class FolderFilesController extends Controller
{
    // アップロード画面を表示する
    public function showUploadForm($id)
    {
        return view('folders.upload', ['folder_id' => $id]);
    }

    // アップロード処理
    public function upload($folderId, Request $request)
    {
        Log::info("Minecraftワールドのアップロード開始");

        // バリデーションルールの定義
        $request->validate([
            'file' => [
                function ($attribute, $value, $fail) {
                    Log::debug("Closure実行: 型=" . gettype($value));

                    if (!($value instanceof UploadedFile)) {
                        Log::error("型不一致: " . gettype($value));
                        return $fail('ファイルが不正です。');
                    }

                    if ($value->getError() !== UPLOAD_ERR_OK) {
                        Log::error("アップロードエラー: コード=" . $value->getError());
                        return $fail('アップロードに失敗しました。');
                    }
                }
            ]
        ]);

        // ファイル情報の取得
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $timestamp = now()->format('Ymd_His');
        $newFileName = "{$timestamp}_{$originalName}";

        // 保存処理
        $path = $file->storeAs(
            "minecraft_worlds/folder_{$folderId}",
            $newFileName,
            'public'
        );

        Log::info("保存完了: {$path}");

        // データベース登録
        FolderFile::create([
            'folder_id' => $folderId,
            'file_name' => $originalName,
            'file_path' => $path,
        ]);

        return redirect()
            ->route('tasks.index', ['id' => $folderId])
            ->with('success', 'Minecraftワールドをアップロードしました: ' . $originalName);
    }


    // ファイル削除処理
    public function destroy($id, Request $request)
    {
        $request->validate([
            'filename' => 'required|string',
        ]);

        $filename = $request->input('filename');
        $filePath = "minecraft_worlds/folder_{$id}/" . $filename;

        Log::debug("削除対象ファイル: {$filePath}");

        // 物理ファイル削除（あれば）
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
            Log::info("ファイル削除成功: {$filePath}");
        } else {
            Log::warning("ファイルが見つからないがDBからは削除: {$filePath}");
        }

        // DBレコード削除
        FolderFile::where('folder_id', $id)
            ->where('file_path', $filePath)
            ->delete();

        return redirect()->route('tasks.index', ['id' => $id])
            ->with('success', 'ファイル情報を削除しました: ' . $filename);
    }
}
