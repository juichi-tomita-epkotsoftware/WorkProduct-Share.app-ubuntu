@extends('admin.base')
@section('title', 'Share.app Remind')

@section('content')
<h1>Remind List</h1>
<a href="{{ route('admin.reminds.create') }}">新規登録</a>

@foreach($reminds as $remind)
<div>
    <p>{{ $remind->remind_date }}</p>
    <p>{{ $remind->title }}</p>
    <p>{{ $remind->comment }}</p>
    @if($remind->image_path)
        <img src="{{ asset('storage/' . $remind->image_path) }}" width="200">
    @endif
</div>
@endforeach
@endsection