<?php

namespace App\Http\Controllers;

use App\Models\Resident;
//DB操作モデルクラス（一覧画面　詳細画面用）
use App\Services\Resident\ResidentService;
//切り分けた住民に対するCRUD処理クラス
use Illuminate\Http\Request;
//クライアントからのリクエストデータを受け取るクラス
use App\Http\Requests\StoreResidentRequest;
//住民操作を行う際のバリデーションをまとめたRequestFormクラス
class ResidentController extends Controller
{
    //ResidentService
    public function __construct(
        private ResidentService $residentService
                //クラス名       プロパティ名=ResidentControllerがもつ入れ物

        //DIを使用:別クラスのインスタンスを変数として受け取って使う仕組み
        //以降$this->residentServiceでインスタンスへのアクセスが出来る
        //プロパティは「箱」　インスタンスは「箱の中に入っている中身」
    ) {}

    //一覧表示画面をかえす
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');
        $keyword = $request->query('keyword');
        //keywordは名前検索ボックスの入力値

        $residents = $this->residentService->getList($filter, $keyword);

        return view('admin.residents.index', compact('residents', 'filter', 'keyword'));
    }

    //登録処理画面をかえす
    public function create()
    {
        return view('admin.residents.create');
    }

    //FormRequestのバリデーション後、StoreResidentRequestのインスタンスへアクセスして登録処理を行う
    public function store(StoreResidentRequest $request)
    {
        $validated = $request->validated();
        //FormRequest使用

        $this->residentService->create(
            data: collect($validated)->except(['image', 'photos'])->toArray(),
            image: $request->file('image'),
            photos: $request->file('photos', []),
        );

        return redirect()->route('admin.residents.index');
    }

    //詳細表示画面をかえす
    public function show(Resident $resident)
    {
        return view('admin.residents.show', compact('resident'));
    }

    //編集表示画面をかえす
    public function edit(Resident $resident)
    {
        if ($resident->user_id !== auth()->id()) {
            abort(403, '他のユーザーのデータは編集できません');
            //abort():処理を強制終了して指定HTTPをかえす。Laravelのグローバルヘルパー関数。
            //403:権限の有無でサーバーから使用を拒否された際のHTTPステータスコード
        }
        return view('admin.residents.edit', compact('resident'));
    }

    //FormRequestのバリデーション後、StoreResidentRequestのインスタンスへアクセスして編集処理を行う
    public function update(StoreResidentRequest $request, Resident $resident)
    {
        if ($resident->user_id !== auth()->id()) {
            abort(403);
        }
        $validated = $request->validated();
        //FormRequest使用

        $this->residentService->update(
            resident: $resident,
            data: collect($validated)->except(['image', 'photos'])->toArray(),
            image: $request->file('image'),
            photos: $request->file('photos', []),
        );

        return redirect()->route('admin.residents.show', $resident);
    }

    //StoreResidentRequestのインスタンスへアクセスして削除処理を行う
    public function destroy(Resident $resident)
    {
        $this->residentService->delete($resident);
        return redirect()->route('admin.residents.index');
    }
}