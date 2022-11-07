<?php
namespace App\Admin\ClientUser\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Contracts\Validation\Validator;
use App\Common\ClientUser\Model\ClientUser;
use App\Common\Database\Definition\AvailableStatus;

class ClientUserStoreRequest extends FormRequest
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
        list($controller, $method) = explode('@', \Route::currentRouteAction());

        switch ($method) {
            case 'store':
            case 'createConfirm':
                $this->redirect = route('admin.client-user.create');
                break;
            default:
                break;
        }

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
        return $this->only(array_merge((new ClientUser)->getFillable(), [ 'password_confirmation' ]));
    }

    /**
     * バリデーションルールの定義を取得する。
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name'         => [ 'required', 'string', 'max:50' ],
            'email'        => [ 'required', 'string', 'email', 'unique:client_users' ],
            'tel'          => [ 'required', 'string', 'max:15', 'tel' ],
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
        return [
            'tel' => 'The :attribute field is unvalid telephone'
        ];
    }

    /**
     * バリデーション要素の表示名の定義を取得する。
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return ClientUser::getAttributeNames();
    }
}
