<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class GetAreaOrderCountRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'addr1_id' => ['required', 'integer'],
            'license'  => ['required', 'array'],
        ];
    }

    /**
     * バリデーションエラーの場合にリダイレクトバックしない
     *
     * @param Validator $validator
     *
     */
    protected function failedValidation(Validator $validator)
    {
        ///
    }
}
