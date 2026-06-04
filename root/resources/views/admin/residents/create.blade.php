@extends('admin.base')
@section('title','Resident information')

@section('content')
<h1>Please enter your personal information</h1>

<form action="{{ route('admin.residents.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div>
        <label>Name</label>
        <input type="text" name="name" value="{{ old('name') }}">
        @error('name') <span>{{ $message }}</span> @enderror
    </div>
    <div>
        <label>Work</label>
        <input type="text" name="job" value="{{ old('job') }}">
    </div>
    <div>
        <label>Likes</label>
        <input type="text" name="likes" value="{{ old('likes') }}">
    </div>
    <div>
        <label>Dislikes</label>
        <input type="text" name="dislikes" value="{{ old('dislikes') }}">
    </div>
    <div>
        <label>Birthplace</label>
        <input type="text" name="birthplace" value="{{ old('birthplace') }}">
    </div>
    <div>
        <label>Age</label>
        <input type="number" name="age" value="{{ old('age') }}">
    </div>
    <div>
        <label>Bio</label>
        <textarea name="bio" rows="10" cols="100">{{old('bio')}}</textarea>
    </div>
    <div>
        <label>Pictures</label>
        <input type="file" name="image" accept="image/*">
    </div>
    <button type="submit">Resister</button>
</form>
@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
@endsection