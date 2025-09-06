@extends('layout')

@section('content')
<div class="container mt-5" style="max-width: 600px;">

    {{-- タイトル --}}
    <div class="mb-4 text-center">
        <h3 class="text-primary">
            <i class="fas fa-user-cog mr-2"></i>ユーザー情報管理
        </h3>
    </div>

    {{-- 成功メッセージ --}}
    @if(session('success'))
    <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    {{-- フォーム --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('user.update') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label for="name"><i class="fas fa-user mr-1 text-secondary"></i>ユーザー名</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ Auth::user()->name }}" required>
                </div>

                <div class="form-group mb-4">
                    <label for="email"><i class="fas fa-envelope mr-1 text-secondary"></i>メールアドレス</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ Auth::user()->email }}" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-50">
                        <i class="fas fa-save mr-1"></i>更新
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
