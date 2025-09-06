<?php

use App\Http\Controllers\FolderController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FolderFilesController;
use App\Http\Controllers\UserController;
/*
 * 認証を求めるミドルウェアのルーティング
 * 機能：ルートグループによる一括適用とミドルウェアによるページ認証
 * 用途：全てのページに対してページ認証を求める
 */
/* certification page （会員登録・ログイン・ログアウト・パスワード再設定など） */
Auth::routes();
Route::group(['middleware' => 'auth'], function() {
    /* home page */
   // Route::get('/', [HomeController::class,"index"])->name('home');
    Route::get('/', function () {
        $user = Auth::user();

        // 仮にユーザーが folder_id を持っているとした場合


        return redirect("/folders/0/tasks");
    });
    /* index page */
    Route::get("/folders/{id}/tasks", [TaskController::class,"index"])->name("tasks.index");
    Route::get('/folders/{id?}/tasks', [TaskController::class, 'index'])->name('tasks.index');

    /* folders new create page */
    Route::get('/folders/create', [FolderController::class,"showCreateForm"])->name('folders.create');
    Route::post('/folders/create', [FolderController::class,"create"]);

    /* folders new edit page */
    Route::get('/folders/{id}/edit', [FolderController::class,"showEditForm"])->name('folders.edit');
    Route::post('/folders/{id}/edit', [FolderController::class,"edit"]);

    /* folders new delete page */
    Route::get('/folders/{id}/delete', [FolderController::class,"showDeleteForm"])->name('folders.delete');
    Route::post('/folders/{id}/delete', [FolderController::class,"delete"]);

    /* tasks new create page */
    Route::get('/folders/{id}/tasks/create', [TaskController::class,"showCreateForm"])->name('tasks.create');
    Route::post('/folders/{id}/tasks/create', [TaskController::class,"create"]);

    /* tasks new edit page */
    Route::get('/folders/{id}/tasks/{task_id}/edit', [TaskController::class,"showEditForm"])->name('tasks.edit');
    Route::post('/folders/{id}/tasks/{task_id}/edit', [TaskController::class,"edit"]);

    /* tasks new delete page */
    Route::get('/folders/{id}/tasks/{task_id}/delete', [TaskController::class,"showDeleteForm"])->name('tasks.delete');
    Route::post('/folders/{id}/tasks/{task_id}/delete', [TaskController::class,"delete"]);


// ファイルアップロード画面の表示（GET）
    Route::get('/folders/{id}/files/upload', [FolderFilesController::class, 'showUploadForm'])->name('tasks.upload.form');
    Route::post('/folders/{id}/files/upload', [FolderFilesController::class, 'upload'])->name('tasks.upload');
    Route::post('/folders/{id}/files/delete', [FolderFilesController::class, 'destroy'])->name('tasks.upload.delete');

    // 自分のプロフィール編集
    Route::get('/user/profile', [UserController::class, 'edit'])->name('user.profile');
    Route::post('/user/update', [UserController::class, 'update'])->name('user.update');
    Route::post('/user/delete', [UserController::class, 'destroy'])->name('user.delete');

    // 管理用ユーザー一覧表示・削除
    Route::get('/user/list', [UserController::class, 'list'])->name('user.list');
    Route::delete('/user/list/{id}', [UserController::class, 'deleteUser'])->name('user.list.delete');
});

