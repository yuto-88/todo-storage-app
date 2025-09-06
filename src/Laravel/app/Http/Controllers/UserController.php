<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class UserController extends Controller
{
public function edit()
{
return view('user.profile');
}

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'ユーザー情報を更新しました。');
    }


public function destroy()
{
$user = Auth::user();
Auth::logout();
$user->delete();

return redirect('/')->with('success', 'ユーザーを削除しました。');
}
    public function list()
    {
        $users = User::all();
        return view('user.list', compact('users'));
    }

    public function deleteUser($id)
    {
        if (auth()->id() == $id) {
            return redirect()->route('user.list')->with('error', '自分自身は削除できません。');
        }

        User::findOrFail($id)->delete();
        return redirect()->route('user.list')->with('success', 'ユーザーを削除しました。');
    }
}
