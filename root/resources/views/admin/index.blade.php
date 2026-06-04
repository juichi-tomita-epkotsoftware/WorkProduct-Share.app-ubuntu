@extends('admin.base')

@section('title', 'Share.app Home')
@section('subtitle', 'Hello')

@section('content')
<div style="max-width: 420px; margin-top: 1rem;">
  <div style="background: #fff; border: 1px solid #e0e0e0; border-radius: 12px; padding: 1.25rem 1.5rem;">

    {{-- 場所と日付 --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:4px;">
      <span style="font-size:13px; color:#888;">📍 Tokyo</span>
      <span style="font-size:13px; color:#888;">{{ now()->isoFormat('YYYY年M月D日（ddd）') }}</span>
    </div>

    {{-- 気温とアイコン --}}
    <div style="display:flex; align-items:flex-end; justify-content:space-between; margin: 0.75rem 0 1rem;">
      <div>
        <div style="font-size:48px; font-weight:500; line-height:1; color:#222;">
          {{ $weather['main']['temp'] }}<span style="font-size:20px;">°C</span>
        </div>
        <div style="font-size:15px; color:#666; margin-top:6px;">
          {{ $weather['weather'][0]['description'] }}
        </div>
      </div>
      <span style="font-size:56px; opacity:0.4;">🌤</span>
    </div>

    {{-- 湿度カード --}}
    <div style="border-top:1px solid #eee; padding-top:1rem; display:grid; grid-template-columns:1fr 1fr; gap:12px;">
      <div style="background:#f7f7f7; border-radius:8px; padding:10px 14px;">
        <div style="font-size:12px; color:#888; margin-bottom:4px;">💧 湿度</div>
        <div style="font-size:22px; font-weight:500; color:#222;">
          {{ $weather['main']['humidity'] }}<span style="font-size:14px; font-weight:400;">%</span>
        </div>
      </div>
      <div style="background:#f7f7f7; border-radius:8px; padding:10px 14px;">
        <div style="font-size:12px; color:#888; margin-bottom:4px;">🌡 気温</div>
        <div style="font-size:22px; font-weight:500; color:#222;">
          {{ $weather['main']['temp'] }}<span style="font-size:14px; font-weight:400;">°C</span>
        </div>
      </div>

    {{-- 住民総数カード --}}
    <div style="background: #fff; border: 1px solid #e0e0e0; border-radius: 12px; padding: 1.25rem 1.5rem; margin-top: 1rem;">
      <div style="font-size: 12px; color: #888; margin-bottom: 4px;">👥 現在の住民数</div>
      <div style="font-size: 36px; font-weight: 500; color: #222; line-height: 1;">
        {{ $residentCount }}<span style="font-size: 16px; font-weight: 400; color: #666;"> 人</span>
      </div>
    </div>

    {{-- 画像 --}}
    <div style="background:#f7f7f7; border-radius:8px; overflow:hidden;">
      <img src="{{ asset('images/けろっぴ.png') }}"
          style="width:100%; height:100%; object-fit:cover; display:block;">
    </div>

    </div>



  </div>
</div>
@endsection


