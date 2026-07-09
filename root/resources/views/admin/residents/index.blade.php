@extends('layouts.admin')
@section('title','Share.app Residents')

@section('content')
<h1>ShareMenbers</h1>
<a href="{{ route('admin.residents.create') }}"
   style="display:inline-block; background:#2e7d32; color:white; text-decoration:none; border-radius:999px; padding:10px 24px; font-size:15px; font-weight:600; letter-spacing:0.5px; margin-bottom:20px;">
    ＋ New registration
</a>

{{-- 全て/現住民/旧住民でフィルタリング --}}
<div style="margin-bottom:20px;">
    @foreach (['all' => 'All', 'current' => 'Current', 'former' => 'Old'] as $key => $label)
    //1周目 $key=all,$label=All
    //2周目 $key=current,$label=Current
    //3周目 $key=former,$label=Old
        <a href="{{ route('admin.residents.index', ['filter' => $key]) }}"
        {{-- route()が引数内で指定されたルート名をUELへ変換する --}}
        {{-- ここでのfilterはURLのパラメータ値ではなくパラメータ名。要は?filter=currentのfilter部分 --}}
           style="display:inline-block; padding:6px 18px; border-radius:999px; text-decoration:none; font-size:14px; margin-right:6px;
                  {{ $filter === $key
                      ? 'background:#2e7d32; color:white; font-weight:600;'
                      : 'background:white; color:#2e7d32; border:1px solid #2e7d32;' }}">
                      {{-- ここで選択中の色を白にしている --}}
            {{ $label }}
        </a>
        //href=リンクの飛び先URLを指定する属性
        //$filter=コントローラから渡された変数(ユーザーはどのフィルタを選んでいるか判断するための変数であり、)
        //ユーザーはどのフィルタを選んでいるか判断するための変数であり、$filterがtrueのときにボタンは緑色(選択中)になるようにしてる
    @endforeach
</div>

<form method="GET" action="{{ url('admin/residents') }}" style="margin: 10px 0;">
    {{-- 検索時もフィルター状態を維持する --}}
    <input type="hidden" name="filter" value="{{ $filter }}">

    <input type="text" name="keyword" value="{{ $keyword }}" placeholder="Search by name">
    <button type="submit" class="btn btn-success">Search</button>

    @if ($keyword)
        <a href="{{ url('admin/residents') }}?filter={{ $filter }}" class="btn btn-outline-secondary">Clear</a>
    @endif
</form>

<table class="table table-bordered table-hover" style="font-size: 18px">
    <tr>
        <th>Name</th><th>Work</th><th>BirthPlace</th><th>Age</th><th style="width: 120px">Action</th>
    </tr>
    @foreach($residents as $resident)
    <tr>
        <td>
            @if($resident->image_path)
                <img src="{{ asset('storage/' . $resident->image_path) }}" width="80">
            @endif
            <a href="{{ route('admin.residents.show', $resident) }}">{{ $resident->name }}</a>
        </td>
        <td>{{ $resident->job }}</td>
        <td>{{ $resident->birthplace }}</td>
        <td>{{ $resident->age }}</td>
        <td>

            @if($resident->user_id === auth()->id())
            <a href="{{ route('admin.residents.edit', $resident) }}">Edit</a>
            <form action="{{ route('admin.residents.destroy', $resident) }}" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit" style="background: none; border:none;padding:0;color:#0d6efd;cursor: pointer;font-size:inherit;" >Delete</button>
            </form>
            @else
            <span class="text-muted">編集不可</span>
            @endif
        </td>
    </tr>
    @endforeach
</table>
@endsection