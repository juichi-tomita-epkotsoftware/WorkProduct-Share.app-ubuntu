@extends('layouts.admin')
@section('title','Test')



@section('content')
<h1>ShareMenbers</h1>

<a href="{{route('admin.tests.create')}}">登録</a>
<br>

    @foreach($testdatas as $testdata)
    <tr>
        <td>
            @if($testdata->image_path)
                <img src="{{ asset('storage/' . $testdata->image_path) }}" width="80">
            @endif
        </td>

        <td>
            {{ $testdata->id }}
        </td>

        <td>
            <form action="{{route('admin.tests.destroy',$testdata)}}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">DELETE</button>
            </form>
        </td>

    </tr>
    @endforeach


@endsection