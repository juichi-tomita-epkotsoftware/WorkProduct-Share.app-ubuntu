<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Http\Request;
//$requestを使えるようにする
use Illuminate\Support\Facades\Storage;
//ファイルの保存・削除を扱うクラス

class ResidentController extends Controller
{
    /**
     *１.住民の一覧表示
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $residents = Resident::all();
        //Residentテーブルの全レコードを取得して$residentsへ格納
        return view('admin.residents.index',compact('residents'));
        //表示Viewの指定とそのViewで配列residentsを使えるようにしている
    }

    /**
     *2.住民の登録処理画面の表示
     *
     * @return \Illuminate\Http\Response
     */
    //登録フォーム
    public function create()
    {
        return view('admin.residents.create');
    }

    /**
     * 3.データの登録
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //登録処理
    public function store(Request $request)
    {
        $request->validate([
            'name'  =>  'required|string|max:100',
            'job'  =>  'required|string|max:100',
            'likes'  =>  'required|string|max:255',
            'dislikes'  =>  'required|string|max:255',
            'birthplace'  =>  'required|string|max:100',
            'age'  =>  'required|integer|min:0|max:150',
            'image'  =>  'required|image|max:2048', //2MBまで
            'bio'  =>  'required|string|max:500', //500文字まで
        ]);

        //ファイルを保存してパスを取得する
        $imagePath = null;
        if($request->hasFile('image')){
            //$requestがimageという名前のファイルだったかどうかでtrue/falseを判定する
            $imagePath = $request->file('image')->store('admin.residents','public');
            //file('A')でフォームから送られたAファイルを取り出す
            //store('B','C')でBという保存先フォルダのCというディスクにファイルを保存
        }


        Resident::create([
            'name'  =>  $request->name,
            'job'  =>  $request->job,
            'likes'  =>  $request->likes,
            'dislikes'  =>  $request->dislikes,
            'birthplace'  =>  $request->birthplace,
            'age'  =>  $request->age,
            //上記は全て文字列のカラムなのでそのままDBへ保存
            'image_path'  =>  $imagePath,
            //画像ファイルはサーバ上のフォルダへ保存
            //保存先のパス文字列をDBへ保存
            'bio'  =>  $request->bio,
        ]);
        return redirect()->route('admin.residents.index');
    }

    /**
     * 4 住民の詳細画面を表示
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Resident $resident)
    {
        return view('admin.residents.show',compact('resident'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Resident $resident)
    {
        return view('admin.residents.edit',compact('resident'));
    }

    /**
     * 5 住民情報の編集
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Resident $resident)
    {
        $request->validate([
            'name'  =>  'required|string|max:100',
            'job'  =>  'required|string|max:100',
            'likes'  =>  'required|string|max:255',
            'dislikes'  =>  'required|string|max:255',
            'birthplace'  =>  'required|string|max:255',
            'age'  =>  'required|integer|min:0|max:150',
            'image'  =>  'required|image|max:2048',
            'bio'  =>  'required|string|max:500', //500文字まで
        ]);

    $imagePath = $resident->image_path;
    if($request->hasFile('image')){
        //古い画像を削除
        if($imagePath){
            Storage::disk('public')->delete($imagePath);
        }
        $imagePath = $request->file('image')->store('residents','public');
    }

    $resident->update([
        'name'      =>$request->name,
        'job'       =>$request->job,
        'likes'     =>$request->likes,
        'dislikes'  =>$request->dislikes,
        'birthplace'=>$request->birthplace,
        'age'       =>$request->age,
        'image_path'=>  $imagePath,
        'bio'  => $request->bio,
    ]);

        return redirect()->route('admin.residents.show',$resident);
    }

    /**
     * 6．画像削除メソッド
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resident $resident)
    {
        if($resident->image_path){
            Storage::disk('public')->delete($resident->image_path);
            //publicディスクを指定して画像ファイルを削除
        }
        $resident->delete();
        //テーブルに保存されたパス文字列を削除
        return redirect()->route('admin.residents.index');
        //route()はルート名指定
    }

    /**
     * 7．リマインド画面の表示メソッド
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function remind()
    {
        $residents = Resident::all();
        return view('admin.residents.remind',compact('residents'));
        //view()は実際のフォルダパス指定
        //conpact():
    }
}
