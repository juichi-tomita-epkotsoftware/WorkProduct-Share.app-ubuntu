@extends('admin.base')
@section('title', 'Share.app Remind')

@section('content')
<h1>Remind List！</h1>
<a href="{{ route('admin.reminds.create') }}"
   style="display:inline-block; background:#2e7d32; color:white; text-decoration:none; border-radius:999px; padding:10px 24px; font-size:15px; font-weight:600; letter-spacing:0.5px; margin-bottom:20px;">
    ＋ New Remind
</a>

<table class="table table-bordered table-hover" style="font-size: 18px">
    <tr>
        <th>Image</th><th>Date</th><th>Title</th><th>Category</th><th>Comment</th><th>DELETE</th>
    </tr>
    @foreach($reminds as $remind)
    <tr>
        <td>
            @if($remind->image_path)
                <img src="{{ asset('storage/' . $remind->image_path) }}" width="80">
            @endif
        </td>
        <td>{{ $remind->remind_date }}</td>
        <td>{{ $remind->title }}</td>
        <td>{{ $remind->category }}</td>
        <td>{{ $remind->comment }}</td>
        <td>
            <form action="{{ route('admin.reminds.destroy', $remind->id) }}" method="POST"
                onsubmit="return confirm('削除しますか?')">
                @csrf
                @method('DELETE')
                <button type="submit" style="background: none; border:none;padding:0;color:#0d6efd;cursor: pointer;font-size:inherit;" >削除</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection