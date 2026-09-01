@extends('layouts.admin')
@section('title', 'Share.app Remind')

@section('content')
<a href="{{ route('user.reminds.create') }}"
   style="display:inline-block; background:#2e7d32; color:white; text-decoration:none; border-radius:999px; padding:10px 24px; font-size:15px; font-weight:600; letter-spacing:0.5px; margin-bottom:20px;">
    ＋ New Remind
</a>

<div class="row">
    {{-- 左：一覧 --}}
    <div class="col-md-8">
        <h5 class="mb-3">
            {{ $category ? $category : '直近のリマインド（5件）' }}
        </h5>

        <table class="table table-bordered table-hover" style="font-size:18px; width:auto;">
            <tr>
                <th>Image</th><th>Date</th><th>Title</th><th>Category</th>
            </tr>
            @forelse($reminds as $remind)
            <tr>
                <td>
                    @if($remind->image_path)
                        <img src="{{ asset('storage/' . $remind->image_path) }}" width="80">
                    @endif
                </td>
                <td>{{ $remind->remind_date }}</td>
                <td>{{ $remind->title }}</td>
                <td>{{ $remind->category }}</td>
            </tr>
            @empty
            <tr><td colspan="4">リマインドはありません</td></tr>
            @endforelse
        </table>

        @if($reminds instanceof \Illuminate\Contracts\Pagination\Paginator)
            {{ $reminds->links() }}
        @endif
    </div>

    {{-- 右：カテゴリ絞り込み --}}
    <div class="col-md-4">
        <h5 class="mb-3">Category</h5>
        <div class="list-group">
            <a href="{{ route('user.reminds.index') }}"
               class="list-group-item list-group-item-action {{ $category ? '' : 'active' }}">
                All（新着5件）
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('user.reminds.index', ['category' => $cat]) }}"
                   class="list-group-item list-group-item-action {{ $category === $cat ? 'active' : '' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection