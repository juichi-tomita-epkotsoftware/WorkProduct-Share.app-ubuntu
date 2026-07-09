<?php

namespace App\Services\Line;

use Illuminate\Support\Facades\Http;

class LineNotifyService
{
    public function sendMessage(string $message): void
    {
        Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.line.channel_access_token'),
            'Content-Type' => 'application/json',
        ])->post('https://api.line.me/v2/bot/message/push',[
            'to'    => config('services.line.group_id'),
            'messages'=>[
                ['type' => 'text','text'=> $message],
            ],
        ]);
    }
}