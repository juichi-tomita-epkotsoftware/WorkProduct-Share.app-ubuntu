@extends('admin.base')
<!-- extends:どのレイアウトを使うか宣言 -->
@section('title', '職業')
@section('subtitle', '一覧')
@section('css')
<!-- section:どこに何を差し込むかの指定 -->
<!--
・()内にキーと値をセットで書く場合は１行簡潔。キーのみの記載はブロックで囲み閉じるまで全部を値にできる
・今は追加CSSは不要だが、加える場合の枠
-->
@endsection
<!-- アットマークから始める命令：ディレクティブ -->

@section('content')
<!-- 'content' という名前の受け取り口に、<div>〜</div> のHTML全体を差し込んでいるイメージ -->
<div>
  <div class="container">
    <div class="row">
      <div class="col text-right">
        <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary">新規</a>
        <form action="{{ route('admin.jobs.csv') }}" method="POST" style="display:inline;">
          @csrf
          <!-- フォームを送信するとき、「本当にこのサイトから送られたリクエストか」を確認するためのトークン -->
          <button type="submit" class="btn btn-primary">CSV</button>
        </form>
        <form action="{{ route('admin.jobs.tsv') }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-primary">TSV</button>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="table-responsive">
  <p>{{ $jobs->total() }}&nbsp;件</p>
  <!-- この変数はコントローラーがDBから取得しBladeに渡す-->
  <table class="table table-striped table-sm">
    <thead>
      <tr>
        <th>ID</th>
        <th>名称</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    @foreach ($jobs as $job)
      <tr>
        <td>{{ $job->id }}</td>
        <td><a href="{{ route('admin.jobs.show', ['job' => $job]) }}">{{ $job->name }}</a></td>
        <td>
          <button type="button" class="btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-action="{{ route('admin.jobs.destroy', ['job' => $job]) }}" data-text="{{ $job->id . ':' . $job->name }}">
            <span data-feather="trash-2"></span>
          </button>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
  {{ $jobs->links() }}
  <!-- Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">削除確認</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="alert alert-danger" role="alert">
            <span id="deleteTargetText"></span> を削除してもよろしいですか？
          </div>
        </div>
        <div class="modal-footer">
          <form method="POST">
            @method('DELETE')
            @csrf
            <button type="submit" class="btn btn-danger">OK</button>
          </form>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
  $(function () {
    // Modal
    $('#deleteModal').on('show.bs.modal', function (event) {
      const button = $(event.relatedTarget);
      const modal = $(this);

      modal.find('#deleteTargetText').text(button.data('text'));
      modal.find('form').attr('action', button.data('action'));
    })
  });
</script>
@endsection
