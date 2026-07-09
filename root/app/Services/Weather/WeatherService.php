<?php

namespace App\Services\Weather;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    public function getTokyo()
    {
        try{
            $response = Http::timeout(5)->get(
                'https://api.openweathermap.org/data/2.5/weather',
                [
                    'q'=>'Tokyo',
                    'appid' => config('services.openweather.key'),
                    'units' => 'metric',
                    'lang' => 'ja',
                ]
            );

            return $response->successful()
                ? $response->json()
                //もしレスポンスが正常に帰ってくればそれをJSON形式にする
                //successful():200~299のステータスコードならtrue
                : $this->getDefaultWeather();
                //そうでなければ以下で定義しているgetDefaultWeather()を呼び出す
        } catch (\Exception $e){
            Log::error('WeatherAPI Error:'. $e->getMessage());
            return $this->getDefaultWeather();
        }
    }

    private function getDefaultWeather()
    {
        return [
            'main' => ['temp' => 'N/A'],
            // N/A:Not Available(利用不可・データ無し)
            'weather' => ['description' => '取得失敗']
        ];
    }

}