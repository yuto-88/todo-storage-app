<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function destroy($id)
    {
        if (auth()->id() == $id) {
            return redirect()->route('admin.users')->with('error', '自分自身は削除できません。');
        }

        User::findOrFail($id)->delete();
        return redirect()->route('admin.users')->with('success', 'ユーザーを削除しました。');
    }
}

