<?php

namespace App\Common\Customer\Model;

use App\Common\Database\Definition\DatabaseDefs;
use App\Common\Database\MysqlCryptorTrait;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;

/**
 * Customer情報のモデル。
 * @package \App\Common\Customer
 *
 * @method \Illuminate\Database\Eloquent\Builder whereMultiConditions(array $searchConditions)
 */
class Customer extends User
{
    use HasFactory, SoftDeletes, Notifiable, MysqlCryptorTrait;

    /**
     * テーブル名の定義。
     * @var string
     */
    const TABLE_NAME = 'customer_users';

    /**
     * モデルが使用するテーブル名の定義。
     * @var string
     */
    protected $table = self::TABLE_NAME;

    /**
     * このモデルが使用する基本の接続名の定義。
     * @var string
     */
    protected $connection = DatabaseDefs::CONNECTION_NAME_READ;

    /**
     * モデルのPrimaryKeyとなるカラムの名称。
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * create()等で値の代入が許可される項目の定義。
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'tel',
        'birthday',
        'email',
        'password',
        'gender',
        'address',
        'avatar'
    ];

    /**
     * 配列化やjson化される場合など、値を取得する必要のないプロパティの定義。
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * 取得時にキャストされるプロパティとそのキャスト先のマッピングの定義。
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime:Y/m/d H:i:s',
        'last_login_at'     => 'datetime:Y/m/d H:i:s',
        'created_at'        => 'datetime:Y/m/d H:i:s',
        'updated_at'        => 'datetime:Y/m/d H:i:s',
        'deleted_at'        => 'datetime:Y/m/d H:i:s',
    ];

    /**
     * パラメーター毎の表示名の定義を取得する。
     * ※ FormRequestで使用するものだけ定義すればOK。
     * @return array<string, array<int,string>>
     */
    public static function getAttributeNames(): array
    {
        return [
            'id'                 => '「ID」',
            'name'               => '「Name」',
            'tel'                => '「Tel」',
            'birthday'           => '「Birthday」',
            'email'              => '「Email」',
            'password'           => '「Password」',
            'address'            => '「Address」',
            'gender'             => '「Gender」',
            'avatar'             => '「Avatar」'
        ];
    }

    /**
     * 通知に使用するメールアドレスを取得する。
     * @return string
     */
    public function routeNotificationForMail(): string
    {
        return $this->getAttribute('email');
    }

    /**
     * 検索条件の配列からWhere句を設定する。
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  array $searchConditions
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws \Exception
     */
    public function scopeWhereMultiConditions(Builder $builder, array $searchConditions): Builder
    {
        foreach ($searchConditions as $key => $value) {
            match ($key) {
                // id
                'id' => !empty($value) ? $builder->where($this->qualifyColumn('id'), '=', $value) : null,
                //name
                'name' => !empty($value) ? $builder->where($this->qualifyColumn('name'), 'like', "%{$this->encrypt($value)}%") : null,
                //address
                'address' => !empty($value) ? $builder->where($this->qualifyColumn('address'), 'like', "%{$this->encrypt($value)}%") : null,
                // email
                'email' => !empty($value) ? $builder->whereRaw("lower(email) = '". mb_strtolower($value) . "'") : null,
                // tel
                'tel' => !empty($value) ? $builder->where($this->qualifyColumn('tel'), '=', $this->encrypt($value)) : null,

                default => null,
            };
        }

        return $builder;
    }
}
