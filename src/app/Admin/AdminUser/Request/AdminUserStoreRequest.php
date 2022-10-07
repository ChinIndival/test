<?php
namespace App\Admin\AdminUser\Request;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rules\Password;
use App\Common\AdminUser\Model\AdminUser;
use App\Common\Database\Definition\AvailableStatus;

/**
 * 管理ユーザー情報を登録する際のバリデーションを行うクラス。
 * @package \App\Admin\AdminUser
 */
class AdminUserStoreRequest extends FormRequest
{
    /**
     * リクエストが可能かどうかを返す。
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * バリデーターを作成して返す。
     * @param  \Illuminate\Contracts\Validation\Factory $factory
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(Factory $factory): Validator
    {
        $validator = $factory->make(
            $this->validationData(),
            $this->container->call([$this, 'rules']),
            $this->messages(),
            $this->attributes()
        )->stopOnFirstFailure($this->stopOnFirstFailure);

        $validator->after(function($validator) {
            /** @var \Illuminate\Validation\Validator $validator */
            // すでにエラーがある場合は確認しない。
            if ($validator->errors()->isNotEmpty()) {
                return;
            }
        });
        return $validator;
    }

    /**
     * バリデーション対象となるデータを取得する。
     * @return array<int, string>
     */
    public function validationData(): array
    {
        return $this->only((new AdminUser)->getFillable() + [ 'password_confirm' ]);
    }

    /**
     * バリデーションルールの定義を取得する。
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name'         => [ 'required', 'string', 'max:50' ],
            'email'        => [ 'required', 'email', 'max:255' ],
            'tel'          => [ 'required', 'string', 'max:15' ],
            'password'     => [ 'required', 'confirmed', App::isProduction() ? Password::default(): 'string' ],
            'is_available' => [ 'required', 'in:' . join(',', AvailableStatus::values()) ],
        ];
    }

    /**
     * バリデーションエラー時に返却するメッセージの定義を取得する。
     * @return array
     */
    public function messages(): array
    {
        // メッセージはlang下のファイルで管理する。
        // 上書きしたいメッセージがある場合にのみ設定すること。
        return [];
    }

    /**
     * バリデーション要素の表示名の定義を取得する。
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return AdminUser::getAttributeNames();
    }
}
