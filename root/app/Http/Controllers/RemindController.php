<?php

namespace App\Http\Controllers;

use App\Models\Remind;
use App\Services\Remind\RemindService;
//切り分けたリマインドに対するCRUD処理クラス
use App\Http\Requests\StoreRemindRequest;
//リマインド登録時のバリデーションをまとめたFormRequestクラス

class RemindController extends Controller
{
    //RemindServiceをコンストラクタDIで受け取る(ResidentControllerと同じパターン)
    public function __construct(
        private RemindService $remindService
    ) {}

    //一覧表示画面をかえす
    public function index()
    {
        $reminds = $this->remindService->getList();
        return view('admin.reminds.indexremind', compact('reminds'));
    }

    //登録フォーム画面をかえす
    public function create()
    {
        return view('admin.reminds.createremind');
    }

    //FormRequestのバリデーション後、サービスクラスに登録処理を委譲する
    public function store(StoreRemindRequest $request)
    {
        $validated = $request->validated();

        $this->remindService->create(
            data: collect($validated)->except(['image'])->toArray(),
            image: $request->file('image'),
        );

        return redirect()->route('admin.reminds.index');
    }

    //サービスクラスに削除処理を委譲する
    public function destroy(Remind $remind)
    {
        $this->remindService->delete($remind);
        return redirect()->route('admin.reminds.index');
    }
}