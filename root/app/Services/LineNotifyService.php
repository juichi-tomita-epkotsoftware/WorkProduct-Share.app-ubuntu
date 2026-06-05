<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class LineNotifyService
{
    public function sendMessage(string $message): void
    {
        Http::withHeaders([
            'Authorization' => 'Bearer ' . env('LINE_CHANNEL_ACCESS_TOKEN'),
            'Content-Type' => 'application/json',
        ])->post('https://api.line.me/v2/bot/message/push',[
            'to'    => env('LINE_GROUP_ID'),
            'messages'=>[
                ['type' => 'text','text'=> $message],
            ],
        ]);
    }
}