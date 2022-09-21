<?php
namespace GLC\Client\Auth\Forms;

use Illuminate\Foundation\Http\FormRequest;

/**
 * パスワード再発行のメール送信処理用のFormRequestクラス。
 *
 * @package GLC\Client\Auth\Forms
 * @author  TinhNC <tinhhang22@gmail.com>
 */
class PasswordResetMailRequest extends FormRequest
{
    /**
     * 認証が必要なリクエストかどうかを返す。
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * バリデーション対象となるデータを取得する。
     *
     * @return array
     */
    public function validationData(): array
    {
        return $this->only([
            'id',
            'email',
        ]);
    }

    /**
     * バリデーションルールの配列を取得する。
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id'    => 'required|string',
            'email' => 'required|email',
        ];
    }

    /**
     * バリデーションメッセージの配列を取得する。
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'id.required'    => ':attributeを入力してください。',
            'id.string'     => ':attributeの形式が正しくありません。',
            'email.required' => ':attributeを入力してください。',
            'email.email'    => ':attributeの形式が正しくありません。',
        ];
    }

    /**
     * バリデーション要素の表示名の配列を取得する。
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'id'    => '「ログインID」',
            'email' => '「メールアドレス」',
        ];
    }
}