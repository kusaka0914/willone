<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CompanyEntryRequest extends FormRequest
{
    const EXCEPT_CHARS = ['　', "\t", ' '];

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

        foreach ($params as $key => $val) {
            if ($key == 'name_kan' || $key == 'name_cana') {
                $params[$key] = str_replace(self::EXCEPT_CHARS, "", $val);
            } elseif ($key == 'birth_year' || $key == 'zip' || $key == 'mob_phone') {
                // [FIXME] birth_yearとmob_phoneパラメータがないなら条件から削除したい
                // [FIXME] zipも永続化してないから加工不要？
                // 全角で書かれている場合半角に変換し、全角スペースを除去
                $val = trim(mb_convert_kana($val, 'as', 'UTF-8'));
                // 半角数字以外の文字列は除去
                $params[$key] = preg_replace('/[^0-9]/', '', $val);
            } elseif ($key == 'mob_mail') {
                // [FIXME] mob_mailパラメータがないならブロック毎削除したい
                // 全角で書かれている場合半角に変換し、全角スペースを除去
                $val = trim(mb_convert_kana($val, 'as', 'UTF-8'));
                // 小文字に変換
                $val = strtolower($val);
                // 半角英数字、特定記号以外の文字列は除去
                $params[$key] = preg_replace('/[^a-z0-9\._@\/\?\+-]/', '', $val);
            }
        }
        // 電話希望時間帯
        if (array_key_exists('tel_time_id', $params) && $params['tel_time_id']) {
            // 選択された項目値を区切り文字「;」で連結する
            $params['tel_time_id'] = implode(";", $params['tel_time_id']);
        }
        // お問合せ内容
        if (array_key_exists('inquiry', $params) && $params['inquiry']) {
            // 選択された項目値を区切り文字「;」で連結する
            $params['inquiry'] = implode(";", $params['inquiry']);
        }
        // オーダー架電窓口
        if (array_key_exists('order_tel_contact', $params) && $params['order_tel_contact']) {
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
            'company_name' => ['required','max:255'],
            'division_name' => ['required','max:255'],
            'name_kan' => ['required','max:80'],
            'name_cana' => ['bail', 'required','max:255'],
            'addr1' => ['required'],
            'addr2' => ['required'],
            'addr3' => ['required','max:255'],
            'tel' => ['bail', 'required', 'custom_tel', 'custom_tel_format', 'custom_tel_length','custom_tel_exist'],
            'mail' => ['required', 'max:80', 'email_format'],
            'inquiry' => ['required'],
            'inquiry_detail' => ['max:3000'],
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
            'company_name' => '貴社名',
            'division_name' => '部署名',
            'name_kan' => '担当者名',
            'name_cana' => 'フリガナ',
            'zip' => '郵便番号',
            'addr1' => '都道府県',
            'addr2' => '市区町村',
            'addr3' => '番地・建物名等',
            'tel' => '電話番号',
            'tel_time_id' => '電話希望時間帯',
            'tel_time_note' => '電話希望時間帯',
            'mail' => 'メールアドレス',
            'inquiry' => 'お問合せ内容',
            'inquiry_detail' => '備考',
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
            'name_cana.zen_hira_kata' => ':attributeは全角ひらがな、全角カタカナで入力してください',
            'tel.custom_tel' => ':attributeに使用できるのは数字と-（ハイフン）、()（カッコ）だけです',
            'tel.min' => ':attributeは10桁以上の数字を入力してください',
            'tel.max' => ':attributeは11桁以下の数字を入力してください',
            'tel.custom_tel_format' => ':attributeの-（ハイフン）、()（カッコ）の入力が正しくありません',
            'mail.email_format' => ':attributeを正しく入力してください',
            'inquiry.required' => ':attributeを選択してください',
        ];
    }

    /**
     * 条件付きValidator
     *
     * @return array
     */
    public function withValidator($validator)
    {
        // 事業所メルマガ以外の場合はフリガナが 全角かな、全角カナのみかを確認する
        $validator->sometimes('name_cana', ['bail', 'required', 'zen_hira_kata'], function ($input) {
            return empty($input->order_tel_contact);
        });
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
