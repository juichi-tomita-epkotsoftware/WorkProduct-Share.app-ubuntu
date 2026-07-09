<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResidentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;   //このリクエストを誰でも実行していいか
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'       => 'required|string|max:100',
            'job'        => 'required|string|max:100',
            'likes'      => 'required|string|max:255',
            'dislikes'   => 'required|string|max:255',
            'birthplace' => 'required|string|max:100',
            'age'        => 'required|integer|min:0|max:150',
            'image'      => 'required|image|max:2048',
            'bio'        => 'required|string|max:500',
            'photos'     => 'nullable|array|max:3',
            'photos.*'   => 'image|max:2048',

            //
        ];
    }
}
