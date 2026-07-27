@extends('layouts.admin')
@section('Test登録','Test登録')

@section('content')
<h1>情報を登録してください</h1>

@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{route('admin.tests.store')}}" method="POST" enctype="multipart/form-data">
<!-- route()は送信のたび最新URLを調べるのではなくページ表示した瞬間にその時点のURLを一回だけ調べて書き込む関数 -->
    @csrf
    <div>Name</div>
        <input type="text" name="name" >

    <div>Work</div>
    <input type="text" name="job" >

    <div>Likes</div>
    <input type="text" name="likes" >

    <div>Dislikes</div>
    <input type="text" name="dislikes" >

    <div>Birthplace</div>
    <input type="text" name="birthplace" >

    <div>Age</div>
    <input type="text" name="age" >

    <div>Bio</div>
    <input type="text" name="bio" >

    <div>Pic</div>
    <input type="file" name="image" accept="image/*" >

    <button type="submit">登録</button>
</form>

@endsection