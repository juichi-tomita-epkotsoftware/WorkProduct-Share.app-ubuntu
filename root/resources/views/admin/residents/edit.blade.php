@extends('admin.base')
@section('title','住民編集')

@section('content')
<h1>住民編集</h1>

<form action="{{ route('admin.residents.update', $resident) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    <div>
        <label>名前（必須）</label>
        <input type="text" name="name" value="{{ old('name', $resident->name) }}">
        @error('name') <span>{{ $message }}</span> @enderror
    </div>
    <div>
        <label>職業</label>
        <input type="text" name="job" value="{{ old('job', $resident->job) }}">
    </div>
    <div>
        <label>好きなもの</label>
        <input type="text" name="likes" value="{{ old('likes', $resident->likes) }}">
    </div>
    <div>
        <label>嫌いなもの</label>
        <input type="text" name="dislikes" value="{{ old('dislikes', $resident->dislikes) }}">
    </div>
    <div>
        <label>出身地</label>
        <input type="text" name="birthplace" value="{{ old('birthplace', $resident->birthplace) }}">
    </div>
    <div>
        <label>年齢</label>
        <input type="number" name="age" value="{{ old('age', $resident->age) }}">
    </div>
    <div>
        <label>写真</label>
        @if($resident->image_path)
            <img src="{{ asset('storage/' . $resident->image_path) }}" width="100">
            <p>新しい写真をアップロードすると置き換わります</p>
        @endif
        <input type="file" name="image" accept="image/*">
    </div>
    <button type="submit">更新</button>
</form>
@endsection