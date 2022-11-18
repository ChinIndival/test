<?php
namespace App\Common\ClientUser\Model;

use Database\Factories\ClientUserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Common\Database\Definition\DatabaseDefs;
use App\Common\Database\MysqlCryptorTrait;

/**
 * 企業ユーザー情報のモデル。
 * @package \App\Common\ClientUser
 *
 * @method \Illuminate\Database\Eloquent\Builder whereMultiConditions(array $searchConditions)
 */
class ClientUser extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable, MysqlCryptorTrait;

    /**
     * テーブル名の定義。
     * @var string
     */
    const TABLE_NAME = 'client_users';

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
        'email',
        'tel',
        'password',
        'is_available',
        'agency_id'
    ];

    /**
     * 配列化やjson化される場合など、値を取得する必要のないプロパティの定義。
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
            'id'               => '「ID」',
            'name'             => '「Name」',
            'email'            => '「Email」',
            'tel'              => '「Tel」',
            'password'         => '「Password」',
            'is_available'     => '「Status」',
        ];
    }

    /**
     * モデルに対応したファクトリークラスのインスタンスを取得する。
     * @return \Database\Factories\ClientUserFactory
     */
    protected static function newFactory(): ClientUserFactory
    {
        return new ClientUserFactory();
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
                // email
                'email' => !empty($value) ? $builder->whereRaw("lower(email) = '". mb_strtolower($value) . "'") : null,
                // tel
                'tel' => !empty($value) ? $builder->where($this->qualifyColumn('tel'), '=', $this->encrypt($value)) : null,
                // is_available
                'is_available' => !empty($value) ? $builder->where($this->qualifyColumn('is_available'), '=', $value) : null,

                default => null,
            };
        }

        return $builder;
    }
}
