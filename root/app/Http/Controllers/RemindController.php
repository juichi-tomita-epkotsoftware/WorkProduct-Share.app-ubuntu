<?php

namespace App\Http\Controllers;

use App\Models\Remind;
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
        $request->validate([
            'title'     => 'required|string|max:100',
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
        'comment'     => $request->comment,
        'image_path'     => $imagePath,
        'remind_date'     => $request->remind_date,
    ]);
    return redirect()->route('admin.reminds.index');
    }
}
