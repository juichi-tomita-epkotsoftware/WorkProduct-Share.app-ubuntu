@extends('admin.base')
@section('title', 'Test202605211128')

@section('content')

  <h2>Remind Report</h2>
  
  <p>総人数：{{ $residents->count() }} 人</p>

  {{-- ====================== --}}
  {{-- @forelse = @foreach + @empty を合体させたもの --}}
  {{-- $residentsが空なら@emptyブロックが表示される  --}}
  {{-- ====================== --}}
  @forelse ($residents as $resident)

    <div class="card mb-2 p-3">

      {{-- @for: 上位3件に「注目」バッジをつける --}}
      @for ($i = 1; $i <= 3; $i++)
        @if ($loop->index === $i - 1)
          <span class="badge badge-warning">注目No.{{ $i }}</span>
        @endif
      @endfor

      <strong>{{ $resident->name }}</strong>
      （{{ $resident->birthplace }}出身）

      {{-- @if / @else: 年齢で出し分け --}}
      @if ($resident->age < 30)
        <span class="badge badge-success">若手</span>
      @else
        <span class="badge badge-secondary">ベテラン</span>
      @endif

    </div>

  @empty
    {{-- $residentsが空（0件）のときここが表示される --}}
    <p class="text-muted">入居者が登録されていません。</p>
  @endforelse

@endsection