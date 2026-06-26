@extends('layouts.admin')
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
        <label>Image（optional）</label>
        <input type="file" name="image" accept="image/*">
    </div>
    <div>
        <label>Title</label>
        <input type="text" name="title" value="{{ old('title') }}">
    </div>
    <div>
        <label>Category</label>
        <select name="category">
            <option value=""></option>
            <option value="Kitchen"     {{ old('category') == 'Kitchen' ? 'selected':''}}>Kitchen</option>
            <option value="Shower Room"     {{ old('category') == 'Shower Room' ? 'selected':''}}>Shower Room</option>
            <option value="Work Space"     {{ old('category') == 'Work Space' ? 'selected':''}}>Work Space</option>
            <option value="Trash"     {{ old('category') == 'Trash' ? 'selected':''}}>Trash</option>
            <option value="The other"     {{ old('category') == 'The other' ? 'selected':''}}>The other</option>
            {{-- old('')はバリデーションエラー時に選択肢を保持するため --}}
        </select>
    </div>
    <div>
        <label>Comment</label>
        <textarea name="comment" rows="4" cols="50">{{ old('comment') }}</textarea>
    </div>
    <div>
        <label>Date</label>
        <input type="date" name="remind_date" value="{{ old('remind_date') }}">
    </div>
    <button type="submit">Registration Start!</button>
</form>
@endsection