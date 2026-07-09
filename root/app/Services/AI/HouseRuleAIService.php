<?php

namespace App\Services\AI;

use OpenAI\Client;
use Illuminate\Support\Facades\Log;

class HouseRuleAIService
{
    private Client $client;
    private string $houseRules;

    public function __construct()
    {
        $this->client = \OpenAI::client(config('services.openai.api_key'));

        // ハウスルールをファイルから読み込む
        $rulesPath = storage_path('rules/house_rules.txt');
        $this->houseRules = file_exists($rulesPath)
            ? file_get_contents($rulesPath)
            : 'ハウスルールが見つかりません';
    }

    public function answer(string $question): string
    {
        try {
            $response = $this->client->chat()->create([
                'model' => 'gpt-4o-mini', // コスト重視なら gpt-4o-mini
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "あなたはシェアハウスの管理アシスタントです。以下のハウスルールに基づいて、住民の質問に丁寧に答えてください。ルールに書いていないことについては、『ハウスルールに記載されていないため、管理者にご相談ください』と答えてください。\n\n【ハウスルール】\n{$this->houseRules}"
                    ],
                    [
                        'role' => 'user',
                        'content' => $question
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 500,
            ]);

            return $response->choices[0]->message->content;

        } catch (\Exception $e) {
            \Log::error('HouseRuleAI Error: ' . $e->getMessage());
            return 'すみません。現在回答システムが利用できません。管理者にお問い合わせください。';
        }
    }
}