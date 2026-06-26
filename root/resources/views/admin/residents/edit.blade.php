@extends('layouts.admin')
@section('title','Edit Page')

@section('content')
<h1>Edit Page</h1>

@if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.residents.update', $resident) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    <div>
        <label>Name（Must）</label>
        <input type="text" name="name" value="{{ old('name', $resident->name) }}">
        @error('name') <span>{{ $message }}</span> @enderror
    </div>
    <div>
        <label>Work（Must）</label>
        <input type="text" name="job" value="{{ old('job', $resident->job) }}">
    </div>
    <div>
        <label>Likes（Must）</label>
        <input type="text" name="likes" value="{{ old('likes', $resident->likes) }}">
    </div>
    <div>
        <label>Dislikes（Must）</label>
        <input type="text" name="dislikes" value="{{ old('dislikes', $resident->dislikes) }}">
    </div>
    <div>
        <label>BithPlace（Must）</label>
        <input type="text" name="birthplace" value="{{ old('birthplace', $resident->birthplace) }}">
    </div>
    <div>
        <label>Age（Must）</label>
        <input type="number" name="age" value="{{ old('age', $resident->age) }}">
    </div>
    </div>
        <div>
        <label>Bio（Any）</label>
        <textarea name="bio" rows="10" cols="100">{{old('bio',$resident->bio)}}</textarea>
    </div>

    <div>
        <label>Picture(Any)</label>
        @if($resident->image_path)
            <img src="{{ asset('storage/' . $resident->image_path) }}" width="100">
            <p>It will be replaced when you upload a new photo</p>
        @endif
        <input type="file" name="image" accept="image/*">

    <div>
        <label>Moved out date（If left blank, current resident）</label>
        <input type="date" name="moved_out_at" value="{{ old('moved_out_at', $resident->moved_out_at) }}">
    </div>

    {{-- formタグに enctype="multipart/form-data" があるか確認（既存の画像アップがあるなら多分ある） --}}
    <div>
        <label>Pic（Up to 3）</label>
        <input type="file" name="photos[]" multiple accept="image/*">
    </div>

    <button type="submit">UPDATE START!!</button>
</form>
@endsection