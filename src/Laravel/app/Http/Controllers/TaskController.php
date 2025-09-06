<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\Task;
use App\Http\Requests\CreateTask;
use App\Http\Requests\EditTask;
use Illuminate\Support\Facades\Auth;
use App\Models\FolderFile;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * タスク作成フォーム表示
     */
    public function showCreateForm(int $id)
    {
        /** @var App\Models\User **/
        $user = Auth::user();
        $folder = $user->folders()->findOrFail($id);

        return view('tasks/create', [
            'folder_id' => $folder->id,
        ]);
    }

    public function create(int $id, CreateTask $request)
    {
        /** @var App\Models\User **/
        $user = Auth::user();
        $folder = $user->folders()->findOrFail($id);

        $task = new Task();
        $task->title = $request->title;
        $task->due_date = $request->due_date;
        $folder->tasks()->save($task);

        return redirect()->route('tasks.index', [
            'id' => $folder->id,
        ]);
    }
    /**
     * タスク編集フォーム表示
     */
    public function showEditForm(int $id, int $task_id)
    {
        /** @var App\Models\User **/
        $user = Auth::user();
        $folder = $user->folders()->findOrFail($id);

        $task = $folder->tasks()->findOrFail($task_id);


        return view('tasks/edit', [
            'task' => $task,
        ]);
    }

    public function edit(int $id, int $task_id, EditTask $request)
    {
        /** @var App\Models\User **/
        $user = Auth::user();
        $folder = $user->folders()->findOrFail($id);
        $task = $folder->tasks()->findOrFail($task_id);


        $task->title = $request->title;
        $task->status = $request->status;
        $task->due_date = $request->due_date;
        $task->save();

        return redirect()->route('tasks.index', [
            'id' => $task->folder_id,
        ]);
    }

    /**
     * タスク削除フォーム表示
     */
    public function showDeleteForm(int $id, int $task_id)
    {
        /** @var App\Models\User **/
        $user = Auth::user();
        $folder = $user->folders()->findOrFail($id);
        $task = $folder->tasks()->findOrFail($task_id);

        return view('tasks/delete', [
            'task' => $task,
        ]);
    }

    public function delete(int $id, int $task_id)
    {
        /** @var App\Models\User **/
        $user = Auth::user();
        $folder = $user->folders()->findOrFail($id);
        $task = $folder->tasks()->findOrFail($task_id);

        $task->delete();

        return redirect()->route('tasks.index', [
            'id' => $task->folder_id
        ]);
    }
    public function index($id)
    {

        // ユーザーのフォルダ一覧（昇順）を取得
        $folders = Folder::where('user_id', \Auth::id())
            ->orderBy('id', 'asc')
            ->get();
        $folder = $folders->where('id', $id)->first();
        if (!$folder) {
            $folder = $folders->first();
        }
        // フォルダが1件もない場合は404
        if ($folders->isEmpty()) {
            abort(404, 'フォルダが存在しません');
        }
        if ($id==0) {
            return redirect("folders/".$folder->id."/tasks");
        }
        // タスク取得
        $tasks = $folder->tasks()->get();

        // アップロード済ファイル取得
        $uploadedFiles = FolderFile::with(['folder.user'])
            ->where('folder_id', $folder->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tasks.index', [
            'folders' => $folders,
            'folder_id' => $folder->id,
            'tasks' => $tasks,
            'uploadedFiles' => $uploadedFiles,
        ]);
    }


}
