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

    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');
        //URLの?filter=の値を取得。無ければ'all'をデフォルトにする
        $keyword = $request->query('keyword');

        $residents = Resident::query()
            ->when($filter === 'current', fn ($q) => $q->whereNull('moved_out_at'))
            // ->when($条件, $条件がtrueのとき実行する関数)
            // fnは無名関数(クロージャの短縮記法)
            ->when($filter === 'former',  fn ($q) => $q->whereNotNull('moved_out_at'))
            ->when($keyword,fn ($q) => $q->where('name','like',"%{$keyword}%"))
            //名前の絞り込みで検索。部分一致でも表示されるようlikeを使用
            ->get();
        //when(条件, 処理)：条件がtrueのときだけ処理（絞り込み）を追加する
        //filterがallのときはどちらのwhenも発動せず全件取得になる

        return view('admin.residents.index', compact('residents', 'filter','keyword'));
        //$filterもViewへ渡す（ボタンの選択中表示に使うため）
    }

    /**
     * $query = Resident::query();          // この時点でDBには何も起きてない
     * $query->whereNull('moved_out_at');   //まだ何も起きてない
     * dd($query->toSql());                 //"select * from `residents` where `moved_out_at` is null"
     */

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
            'bio'  =>  'required|string|max:500', //
            'photos'   => 'nullable|array|max:3',   // ←追加：photosは配列で最大3要素
            'photos.*' => 'image|max:2048',         // ←追加：配列の各要素は画像で2MBまで
        ]);


        //ファイルを保存してパスを取得する
        $imagePath = null;
        if($request->hasFile('image')){
            //$requestがimageという名前のファイルだったかどうかでtrue/falseを判定する
            $imagePath = $request->file('image')->store('admin.residents','public');
            //file('A')でフォームから送られたAファイルを取り出す
            //store('B','C')でBという保存先フォルダのCというディスクにファイルを保存
        }


        $resident = Resident::create([
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

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('resident_photos', 'public');
                $resident->images()->create(['image_path' => $path]);
            }
        }

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
            'image'  =>  'nullable|image|max:2048',
            'bio'  =>  'required|string|max:500', //500文字まで
            'moved_out_at' => 'nullable|date',
            'photos'   => 'nullable|array|max:3',   // ←追加
            'photos.*' => 'image|max:2048',         // ←追加
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
        'moved_out_at' => $request->moved_out_at,
    ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('resident_photos', 'public');
                $resident->images()->create(['image_path' => $path]);
            }
        }

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
