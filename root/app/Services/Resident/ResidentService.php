<?php

namespace App\Services\Resident;

use App\Models\Resident;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ResidentService
{
    /**
     * 一覧取得（フィルタ・キーワード検索）
     */
    public function getList(string $filter, ?string $keyword)
    {
        return Resident::query()
            ->with('user')
            ->when($filter === 'current', fn ($q) => $q->whereNull('moved_out_at'))
            //when()：条件付きでクエリ組み立て処理を実行するか決める分岐
            //moved_out_at IS NULL
            ->when($filter === 'former',  fn ($q) => $q->whereNotNull('moved_out_at'))
            //moved_out_at IS NOT NULL
            ->when($keyword, fn ($q) => $q->where('name', 'like', "%{$keyword}%"))
            ->get();
    }

    /**
     * 新規登録
     * 引数の?はNULL許容という意味
     * UploadedFileはユーザーがアップロードしたファイルに限定する型指定
     * $photosはユーザーがアップロードしたファイルが複数枚格納されたリストが変数化しているから型指定はarrayにしている
     */
    public function create(array $data, ?UploadedFile $image, array $photos = []): Resident
    {
        $imagePath = $image ? $image->store('admin.residents', 'public') : null;
      //$imagePath = 条件 ? 真のときの値 :　偽のときの値
      //store():第一引数は保存先ディレクトリ　第二引数はディスク名
      //アップロード後のがいるはサーバの一次領域/tmpに置かれる。それをディスクへ移動する関数がstore()

      //...$date：スプレッド演算子。$dataの中身を展開する。

        $resident = Resident::create([
            ...$data,
            'user_id'    => Auth::id(),
            'image_path' => $imagePath,
        ]);

        $this->storePhotos($resident, $photos);

        return $resident;
    }

    /**
     * 更新
     * アイコン写真は既存のものがあれば消える仕様。ただ追加写真はただ増えていく。
     */
    public function update(Resident $resident, array $data, ?UploadedFile $image, array $photos = []): Resident
    {
        $imagePath = $resident->image_path;

        if ($image) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $image->store('residents', 'public');
        }

        $resident->update([
            ...$data,
            'image_path' => $imagePath,
        ]);

        $this->storePhotos($resident, $photos);

        return $resident;
    }

    /**
     * 削除
     */
    public function delete(Resident $resident): void
    {
        if ($resident->image_path) {
            Storage::disk('public')->delete($resident->image_path);
        }
        $resident->delete();
    }

    /**
     * 追加写真の保存（store/updateで共通）
     */
    private function storePhotos(Resident $resident, array $photos): void
    {
        foreach ($photos as $photo) {
            $path = $photo->store('resident_photos', 'public');
            $resident->images()->create(['image_path' => $path]);
        }
    }
}