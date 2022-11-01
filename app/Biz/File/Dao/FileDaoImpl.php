<?php
/*
 * Sunny 2022/9/14 下午5:18
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\File\Dao;

use App\Core\Biz\Dao\Impl\BaseDaoImpl;
use Carbon\Carbon;
use Hyperf\ModelCache\Cacheable;
use Hyperf\Snowflake\Concern\Snowflake;

/**
 * @property int $id
 * @property string $name 文件名称
 * @property string $path 文件链接
 * @property string $localPath 本地路径
 * @property int $createdUserId 创建用户
 * @property Carbon $createdTime 创建时间
 * @property Carbon $updatedTime 更新时间
 * @method void setName(string $name)
 * @method void setLocalPath(string $localPath)
 */
class FileDaoImpl extends BaseDaoImpl
{
    use Snowflake;
    use Cacheable;

    protected ?string $table = 'file';

    protected array $fillable = [
        'id', 'name', 'localPath', 'createdUserId',
    ];

    protected array $casts = [
        'id' => 'integer',
        'createdUserId' => 'integer',
        'createdTime' => 'datetime',
        'updatedTime' => 'datetime',
    ];
}
