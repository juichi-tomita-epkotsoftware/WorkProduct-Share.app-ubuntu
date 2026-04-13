<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
        ];
    }
    public function attributes()
    {
        return [
            'name' => '名称',
        ];
    }

    protected function getRedirectUrl()
    {
        if (request()->routeIs('*.update')) {
            // 確認画面→更新バリデーションエラーの場合、編集画面に遷移。
            //   親クラスのgetRedirectUrlでは、パラメータつきURL生成に対応していないため、以下の方法をとる。
            $url = $this->redirector->getUrlGenerator();
            // 編集画面のURLを取得
            return $url->route('admin.jobs.edit', ['job' => request()->route()->parameter('job')]);
        }
        // 親クラス(Illuminate\Foundation\Http\FormRequest) の getRedirectUrl を実行
        return parent::getRedirectUrl();
    }
}