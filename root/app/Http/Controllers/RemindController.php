<?php

namespace App\Http\Controllers;

use App\Models\Remind;
use App\Services\LineNotifyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class RemindController extends Controller
{
    //一覧
    public function index()
    {
        $reminds = Remind::orderBy('remind_date','asc')->get();
        return view('admin.reminds.indexremind',compact('reminds'));
    }
    //登録フォーム
    public function create()
    {
        return view('admin.reminds.createremind');
    }

    //登録処理
    public function store(Request $request)
    {
        //送信されたデータが正しいか検証
        $request->validate([
            'title'     => 'required|string|max:100',
            'category'     => 'required|in:Kitchen,Shower Room,Work Space,Trash,The other',
            'comment'     => 'required|string|max:1000',
            'image'     => 'nullable|image|max:2048',
            'remind_date'     => 'required|date',
        ]);

    $imagePath =null;
    if($request->hasFile('image')){
        $imagePath = $request->file('image')->store('reminds','public');
    }

    Remind::create([
        'title'     => $request->title,
        'category'     => $request->category,
        'comment'     => $request->comment,
        'image_path'     => $imagePath,
        'remind_date'     => $request->remind_date,
    ]);

    //カテゴリの件数チェック
    $count = Remind::where('category',$request->category)->count();
    if($count >= 5){
        $lineService = new LineNotifyService();
        $lineService->sendMessage(
            // "{$request->category}のリマインドが{$count}件になりました。"
            "I am Tomas"
        );
    }

    return redirect()->route('admin.reminds.index');
    }
}
