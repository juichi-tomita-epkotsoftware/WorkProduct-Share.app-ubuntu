<?php

namespace App\Services\Resident;

use App\Models\Resident;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ResidentService
{
    /**
     * 一覧取得（フィルタ・キーワード検索）
     */
    public function getList(string $filter, ?string $keyword)
    {
        return Resident::query()
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
     */
    public function create(array $data, ?UploadedFile $image, array $photos = []): Resident
    {
        $imagePath = $image ? $image->store('admin.residents', 'public') : null;

        $resident = Resident::create([
            ...$data,
            'image_path' => $imagePath,
        ]);

        $this->storePhotos($resident, $photos);

        return $resident;
    }

    /**
     * 更新
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