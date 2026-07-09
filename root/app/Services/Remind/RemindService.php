<?php

namespace App\Services\Remind;

use App\Models\Remind;
use App\Services\LINE\LineNotifyService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

//リマインドに対するCRUD処理を切り分けたサービスクラス
//配置場所: app/Services/Remind/RemindService.php
class RemindService
{
    //LineNotifyServiceもコンストラクタDIで受け取る
    //元コードの「new LineNotifyService()」を直接書く方式だと
    //RemindServiceがLineNotifyServiceの生成方法を知ってしまい密結合になる
    //DIにしておくとテスト時にモックへ差し替えられる
    public function __construct(
        private LineNotifyService $lineNotifyService
    ) {}

    //一覧取得(remind_dateの昇順)
    public function getList()
    {
        return Remind::orderBy('remind_date', 'asc')->get();
    }

    //登録処理
    //$data: バリデーション済みデータ(imageを除いたもの)
    //$image: アップロードされた画像ファイル(なければnull)
    public function create(array $data, ?UploadedFile $image): Remind
    {
        //画像があればstorage/app/public/remindsに保存してパスを取得
        $imagePath = null;
        if ($image) {
            $imagePath = $image->store('reminds', 'public');
        }

        $remind = Remind::create(array_merge($data, [
            'image_path' => $imagePath,
        ]));

        //カテゴリの件数チェック → 5件以上でLINE通知
        $this->notifyIfCategoryFull($remind->category);

        return $remind;
    }

    //削除処理(画像ファイルも一緒に消す)
    public function delete(Remind $remind): void
    {
        if ($remind->image_path) {
            Storage::disk('public')->delete($remind->image_path);
        }

        $remind->delete();
    }

    //カテゴリの登録件数が閾値以上ならLINE通知を送る
    //「登録後の副作用」なのでprivateメソッドに分離して意図を名前で表現
    private function notifyIfCategoryFull(string $category): void
    {
        $count = Remind::where('category', $category)->count();

        if ($count >= 5) {
            $this->lineNotifyService->sendMessage(
                "{$category}のリマインドが{$count}件になりました。"
            );
        }
    }
}