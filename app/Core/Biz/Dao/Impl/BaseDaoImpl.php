<?php

/*
 * Sunny 2022/8/9 下午1:53
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Core\Biz\Dao\Impl;

use Hyperf\DbConnection\Model\Model as BaseModel;

abstract class BaseDaoImpl extends BaseModel
{
    public const CREATED_AT = 'createdTime';

    public const UPDATED_AT = 'updatedTime';

    public const DELETED_AT = 'deletedTime';

    protected ?string $dateFormat = 'Y-m-d H:i:s';
}
