@extends('admin.base')
@section('title', 'リマインド登録')

@section('content')
<h1>リマインド登録</h1>

@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('admin.reminds.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div>
        <label>タイトル</label>
        <input type="text" name="title" value="{{ old('title') }}">
    </div>
    <div>
        <label>コメント</label>
        <textarea name="comment" rows="4" cols="50">{{ old('comment') }}</textarea>
    </div>
    <div>
        <label>写真（任意）</label>
        <input type="file" name="image" accept="image/*">
    </div>
    <div>
        <label>発信日</label>
        <input type="date" name="remind_date" value="{{ old('remind_date') }}">
    </div>
    <button type="submit">登録</button>
</form>
@endsection