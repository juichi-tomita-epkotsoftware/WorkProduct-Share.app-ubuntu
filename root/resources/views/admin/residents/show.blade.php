@extends('admin.base')
@section('title','Detailed Information')

@section('content')

<div style="display:flex; gap:24px; flex-wrap:wrap;">

    <div style="background:white; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.08); padding:32px; max-width:640px;">
        {{-- ヘッダー部：画像＋名前＋ステータス --}}
        <div style="display:flex; align-items:center; gap:24px; margin-bottom:24px;">
            @if($resident->image_path)
                <img src="{{ asset('storage/' . $resident->image_path) }}"
                    style="width:120px; height:120px; border-radius:50%; object-fit:cover; border:3px solid #2e7d32;">
            @else
                <div style="width:120px; height:120px; border-radius:50%; background:#e8f5e9; display:flex; align-items:center; justify-content:center; color:#2e7d32; font-size:40px; font-weight:600;">
                    {{ mb_substr($resident->name, 0, 1) }}
                </div>
            @endif

            <div>
                <div style="font-size:28px; font-weight:700; margin-bottom:8px;">{{ $resident->name }}</div>
                @if($resident->moved_out_at)
                    <span style="background:#eeeeee; color:#616161; border-radius:999px; padding:4px 14px; font-size:13px; font-weight:600;">
                        退去済み（{{ $resident->moved_out_at }}）
                    </span>
                @else
                    <span style="background:#2e7d32; color:white; border-radius:999px; padding:4px 14px; font-size:13px; font-weight:600;">
                        現住民
                    </span>
                @endif
            </div>
        </div>

        {{-- 詳細項目：ラベルと値の2カラム --}}
        <table style="width:100%; border-collapse:collapse; font-size:15px;">
            @foreach ([
                'Work'       => $resident->job,
                'Likes'      => $resident->likes,
                'Dislikes'   => $resident->dislikes,
                'Birthplace' => $resident->birthplace,
                'Age'        => $resident->age,
                'Bio'        => $resident->bio,
            ] as $label => $value)
            <tr style="border-bottom:1px solid #f0f0f0;">
                <th style="text-align:left; padding:12px 8px; color:#757575; font-weight:600; width:140px;">{{ $label }}</th>
                <td style="padding:12px 8px;">{{ $value }}</td>
            </tr>
            @endforeach
        </table>

        {{-- アクションボタン --}}
        <div style="margin-top:28px; display:flex; gap:10px;">
            <a href="{{ route('admin.residents.edit', $resident) }}"
            style="display:inline-block; background:#2e7d32; color:white; text-decoration:none; border-radius:999px; padding:8px 24px; font-size:14px; font-weight:600;">
                Edit
            </a>
            <a href="{{ route('admin.residents.index') }}"
            style="display:inline-block; background:white; color:#2e7d32; border:1px solid #2e7d32; text-decoration:none; border-radius:999px; padding:8px 24px; font-size:14px; font-weight:600;">
                Return to list
            </a>
        </div>

    </div>

    <div style="background:white; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.08); padding:16px; flex:1; min-width:320px; max-width:560px; display:flex;">

        {{-- 左：フォトカルーセル --}}
        <div style="width:380px; flex-shrink:0;">
            @if($resident->images->count())
                <div style="position:relative;">
                    <img id="carousel-image"
                        src="{{ asset('storage/' . $resident->images->first()->image_path) }}"
                        style="width:100%; height:380px; object-fit:cover; border-radius:12px;">

                    <button onclick="moveCarousel(-1)" style="position:absolute; left:8px; top:50%; transform:translateY(-50%); background:rgba(0,0,0,0.4); color:white; border:none; border-radius:50%; width:36px; height:36px; cursor:pointer; font-size:16px;">❮</button>
                    <button onclick="moveCarousel(1)" style="position:absolute; right:8px; top:50%; transform:translateY(-50%); background:rgba(0,0,0,0.4); color:white; border:none; border-radius:50%; width:36px; height:36px; cursor:pointer; font-size:16px;">❯</button>

                    <div id="carousel-counter" style="position:absolute; bottom:8px; right:12px; background:rgba(0,0,0,0.5); color:white; border-radius:999px; padding:2px 10px; font-size:12px;"></div>
                </div>
            @else
                <div style="width:100%; height:380px; border-radius:12px; background:#e8f5e9; display:flex; align-items:center; justify-content:center; color:#2e7d32;">
                    No Photos
                </div>
            @endif
        </div>

        {{-- 右：前回作ったプロフィール部分をここに丸ごと移動 --}}
        <div style="flex:1;">
            {{-- アイコン＋名前＋バッジ、テーブル、ボタン（前回のコードそのまま） --}}
        </div>

    </div>
</div>
    <script>
        const images = @json($resident->images->map(fn ($img) => asset('storage/' . $img->image_path))->values());
        let current = 0;

        function moveCarousel(dir) {
            current = (current + dir + images.length) % images.length;
            document.getElementById('carousel-image').src = images[current];
            updateCounter();
        }

        function updateCounter() {
            if (images.length) {
                document.getElementById('carousel-counter').textContent = `${current + 1} / ${images.length}`;
            }
        }
        updateCounter();
    </script>

@endsection

