<?php

namespace App\Http\Controllers;

use App\Services\Weather\WeatherService;    // ← 「namespace + クラス名」を指定
//天気のAPIを取得するクラス
use App\Services\Resident\ResidentCount;
//現住人の人数をカウントするクラス

class HomeController extends Controller
{
    public function __construct(
        private WeatherService $weatherService,
        private ResidentCount $residenService
        //private クラス名  変数名
    ){}

    public function index()
    {
        return view('admin.home.index',[
            'weather' => $this->weatherService->getTokyo(),
            //Weatherサービスのメソッド使用
            'residentCount' => $this->residenService->getCount(),
            //Residentサービスのメソッド使用
        ]);
    }
}
