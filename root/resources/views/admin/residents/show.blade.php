@extends('admin.base')
@section('title','Detailed Information')

@section('content')
<h1>{{ $resident->name }}</h1>

@if($resident->image_path)
    <img src="{{ asset('storage/' . $resident->image_path) }}" width="200">
@endif

<ul>
    <li>Work：{{ $resident->job }}</li>
    <li>Likes：{{ $resident->likes }}</li>
    <li>Dislikes：{{ $resident->dislikes }}</li>
    <li>Birthplace：{{ $resident->birthplace }}</li>
    <li>Age：{{ $resident->age }}</li>
    <li>Bio：{{ $resident->bio }}</li>
</ul>

<a href="{{ route('admin.residents.edit', $resident) }}">Edit</a>
<a href="{{ route('admin.residents.index') }}">Return to list</a>
@endsection