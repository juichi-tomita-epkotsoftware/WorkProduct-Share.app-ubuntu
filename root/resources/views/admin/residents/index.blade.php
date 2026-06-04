@extends('admin.base')
@section('title','Share.app Residents')

@section('content')
<h1>ShareMenbers</h1>
<a href="{{ route('admin.residents.create') }}"
   style="display:inline-block; background:#2e7d32; color:white; text-decoration:none; border-radius:999px; padding:10px 24px; font-size:15px; font-weight:600; letter-spacing:0.5px; margin-bottom:20px;">
    ＋ New registration
</a>

<table class="table table-bordered table-hover" style="font-size: 18px">
    <tr>
        <th>名前</th><th>職業</th><th>出身地</th><th>年齢</th><th style="width: 120px">操作</th>
    </tr>
    @foreach($residents as $resident)
    <tr>
        <td>
            @if($resident->image_path)
                <img src="{{ asset('storage/' . $resident->image_path) }}" width="80">
            @endif
            {{ $resident->name }}
        </td>
        <td>{{ $resident->job }}</td>
        <td>{{ $resident->birthplace }}</td>
        <td>{{ $resident->age }}</td>
        <td style="white-space: nowrap; width:120px">{{ $resident->age }}</td>
        <td>
            <a href="{{ route('admin.residents.show', $resident) }}">詳細</a>
            <a href="{{ route('admin.residents.edit', $resident) }}">編集</a>
            <form action="{{ route('admin.residents.destroy', $resident) }}" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit" style="background: none; border:none;padding:0;color:#0d6efd;cursor: pointer;font-size:inherit;" >削除</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection