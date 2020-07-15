<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
// [https://liginc.co.jp/359544]を参考に作ったよ！
class sample001Request extends FormRequest
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
        // 入力チェック条件指定する
		$validate_rule;
		// 名前｜必須
		$validate_rule['name'] = 'required';
		// メール｜フォーマット
		$validate_rule['mail'] = 'email';
		//　年齢｜数値、0～150
		$validate_rule['age'] = 'numeric|between:0,150';
        return $validate_rule;
    }

    // メッセージ指定
    public function messages()
    {
        return [
            'name.required' => ':attributeは必須ですよー',
            'mail.email'  => ':attributeはメールアドレスではありません',
            'age.between'  => ':attributeは:minから:maxまで入力してください',
            'age.numeric'  => ':attributeは半角数字で入力してください',
        ];
    }

    // 項目名称を指定する
    public function attributes()
    {
        return [
            'name' => '名前',
            'mail' => 'メール',
            'age' => '年齢',
        ];
    }
}
