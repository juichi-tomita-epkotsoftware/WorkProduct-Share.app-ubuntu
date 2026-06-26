<?php

namespace App\Http\Controllers;

use App\Services\HouseRuleAIService;
use Illuminate\Http\Request;

class HouseRuleQAController extends Controller
{
    private HouseRuleAIService $aiService;

    public function __construct(HouseRuleAIService $aiService)
    {
        $this->aiService = $aiService;
    }

    // Q&A ページの表示
    public function index()
    {
        return view('admin.house_rule_qa.index');
    }

    // API エンドポイント：質問に回答する
    public function ask(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
        ]);

        $answer = $this->aiService->answer($validated['question']);

        return response()->json([
            'question' => $validated['question'],
            'answer' => $answer,
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}