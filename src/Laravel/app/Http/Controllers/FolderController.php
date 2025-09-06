<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use App\Http\Requests\CreateFolder;
use App\Http\Requests\EditFolder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FolderController extends Controller
{
    public function showCreateForm()
    {
        // ログインユーザーに紐づくフォルダだけを取得
        $folders = Auth::user()->folders;

        return view('folders/create', compact('folders'));
    }

    public function create(CreateFolder $request)
    {
        $folder = new Folder();
        $folder->title = $request->title;
        // （ログイン）ユーザーに紐づけて保存する
        Auth::user()->folders()->save($folder);

        return redirect()->route('tasks.index', [
            'id' => $folder->id,
        ]);
    }

    /**
     *  【フォルダ編集ページの表示機能】
     *
     *  GET /folders/{id}/edit
     */
    public function showEditForm(int $id)
    {
        /** @var App\Models\User **/
        $user = Auth::user();
        $folder = $user->folders()->findOrFail($id);

        return view('folders/edit', [
            'folder_id' => $folder->id,
            'folder_title' => $folder->title,
        ]);
    }

    public function edit(int $id, EditFolder $request)
    {
        /** @var App\Models\User **/
        $user = Auth::user();
        $folder = $user->folders()->findOrFail($id);
        $folder->title = $request->title;
        $folder->save();

        return redirect()->route('tasks.index', [
            'id' => $folder->id,
        ]);
    }

    /**
     *  【フォルダの削除機能】
     *
     *  POST /folders/{id}/delete
     */
    public function showDeleteForm(int $id)
    {
        /** @var App\Models\User **/
        $user = Auth::user();
        $folder = $user->folders()->findOrFail($id);

        return view('folders/delete', [
            'folder_id' => $folder->id,
            'folder_title' => $folder->title,
        ]);
    }

    public function delete(int $id)
    {
        /** @var App\Models\User * */
        $user = Auth::user();
        $folder = $user->folders()->findOrFail($id);

        // フォルダを削除する前に次に表示するフォルダを取得
        $nextFolder = $user->folders()->where('id', '!=', $folder->id)->first();

        // タスクとフォルダを削除
        $folder->tasks()->delete();
        $folder->delete();

        return redirect()->route('tasks.index', ['id' => $nextFolder->id]);
    }

}
