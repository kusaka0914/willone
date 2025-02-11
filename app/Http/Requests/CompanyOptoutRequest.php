<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CompanyOptoutRequest extends FormRequest
{
    public function validator($factory)
    {
        $normalizedParams = $this->normalize();

        $validator = $factory->make(
            $normalizedParams,
            $this->container->call([$this, 'rules']),
            $this->messages(),
            $this->attributes()
        );

        return $validator;
    }

    /**
     * リクエストパラメータを仕様に合わせて加工する
     *
     * @return array
     */
    public function normalize()
    {
        $params = $this->input();

        // オーダー架電窓口
        if ($params['order_tel_contact']) {
            $params['order_tel_contact'] = substr($params['order_tel_contact'], 0, -5);
        }

        return $params;
    }

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
            'mail' => ['required', 'max:80', 'email_format'],
            'stop_reason' => ['required'],
        ];
    }

    /**
     * 項目名マッピング
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'mail' => 'メールアドレス',
            'stop_reason' => '配信停止理由',
        ];
    }

    /**
     * エラーメッセージマッピング
     *
     * @return array
     */
    public function messages()
    {
        return [
            'mail.email_format' => ':attributeを正しく入力してください',
            'stop_reason.required' => ':attributeを選択してください',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($this->input())
        );
    }
}
