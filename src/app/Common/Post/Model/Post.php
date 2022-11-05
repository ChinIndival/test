<?php
namespace App\Common\Post\Model;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Common\Database\Definition\DatabaseDefs;

/**
 * 管理ユーザー情報のモデル。
 * @package \App\Common\Sample
 *
 * @method \Illuminate\Database\Eloquent\Builder whereMultiConditions(array $searchConditions)
 */
class Post extends Model
{
    use SoftDeletes;

    /**
     * テーブル名の定義。
     * @var string
     */
    const TABLE_NAME = 'client_posts';

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
        'title',
        'status',
        'avatar',
        'content',
        'city',
        'district',
        'address',
        'price',
        'area',
        'client_id',
        'view_counts'
    ];


    /**
     * 取得時にキャストされるプロパティとそのキャスト先のマッピングの定義。
     * @var array<string, string>
     */
    protected $casts = [
        'closed_at'         => 'datetime:Y/m/d H:i:s',
        'published_at'      => 'datetime:Y/m/d H:i:s',
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
            'title'              => '「Title」',
            'status'             => '「Status」',
            'avatar'             => '「Avatar」',
            'content'            => '「Content」',
            'city'               => '「City」',
            'district'           => '「District」',
            'address'            => '「Address」',
            'price'              => '「Price」',
            'area'               => '「Area」',
            'Client_id'          => '「Client_id」',
            'view_counts'        => '「View_counts」',
        ];
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
                // title
                'title' => !empty($value) ? $builder->where($this->qualifyColumn('title'), 'like', "%{$value}%") : null,
                // content
                'content' => !empty($value) ? $builder->where($this->qualifyColumn('content'), 'like', "%{$value}%") :null,
                // status
                'status' => !empty($value) ? $builder->where($this->qualifyColumn('status'), '=', $value) : null,
                // address
                'address' => !empty($value) ? $builder->where($this->qualifyColumn('address'),'=', $value) : null,
                default => null,
            };
        }

        return $builder;
    }
}