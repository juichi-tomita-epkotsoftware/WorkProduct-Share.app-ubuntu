<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//フォーム・URLからのデータを受け取るクラス
use Illuminate\Support\Facades\Http;
//外部APIにHTTPリクエストを送るクラス
use App\Models\Resident;

class HomeController extends Controller
{
    public function index()
    {
        $residentCount = Resident::count();

        $response = Http::get('https://api.openweathermap.org/data/2.5/weather',[
            'q' => 'Tokyo',
            'appid' => env('OPENWEATHER_API_KEY'),
            'units' => 'metric',
            'lang' => 'ja',
        ]);
        //これはURL宛てに送る設定値

        $weather = $response->json();
        return view('admin.home.index',compact('weather','residentCount'));
    }

}
