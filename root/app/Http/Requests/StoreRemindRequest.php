<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

//リマインド登録時のバリデーションをまとめたFormRequestクラス
//作成コマンド: php artisan make:request StoreRemindRequest
class StoreRemindRequest extends FormRequest
{
    //このリクエストの実行を許可するか(認可)
    //ルート側でauthミドルウェアを通しているなら基本trueでOK
    public function authorize(): bool
    {
        return true;
    }

    //バリデーションルール
    //元々コントローラのstore()内にあった$request->validate()の中身をそのまま移動
    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:100',
            'category'    => 'required|in:Kitchen,Shower Room,Work Space,Trash,The other',
            'comment'     => 'required|string|max:1000',
            'image'       => 'nullable|image|max:2048',
            'remind_date' => 'required|date',
        ];
    }
}