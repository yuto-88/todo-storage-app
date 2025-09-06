@extends('layout')

@section('content')
<div class="container mt-5" style="max-width: 960px;">

    {{-- 見出し --}}
    <div class="mb-4 text-center">
        <h3 class="text-primary">
            <i class="fas fa-users mr-2"></i>ユーザーの管理
        </h3>
    </div>

    {{-- メッセージ --}}
    @if(session('success'))
    <div class="alert alert-success text-center">{{ session('success') }}</div>
    @elseif(session('error'))
    <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    {{-- テーブルカード --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0 align-middle text-center" style="background-color: #fff;">
                    <thead class="thead-light bg-light">
                    <tr class="align-middle">
                        <th class="py-3" style="width: 30%;">ユーザー名</th>
                        <th class="py-3" style="width: 45%;">メールアドレス</th>
                        <th class="py-3" style="width: 25%;">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="py-3">{{ $user->name }}</td>
                        <td class="py-3">{{ $user->email }}</td>
                        <td class="py-3">
                            <form action="{{ route('user.list.delete', $user->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash-alt mr-1"></i>削除
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-muted py-4">登録されているユーザーがいません。</td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
