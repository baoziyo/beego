<?php
/*
 * Sunny 2021/11/24 下午8:14
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\File\Service;

use App\Biz\File\Dao\FileDaoImpl;
use App\Biz\File\Fields\CreateFileFields;
use App\Core\Biz\Service\BaseService;
use Hyperf\Database\Model\Collection;
use Hyperf\HttpMessage\Upload\UploadedFile;

interface FileService extends BaseService
{
    public function get(int $id): FileDaoImpl|null;

    public function getById(int $id): FileDaoImpl;

    public function find(array $ids): Collection;

    public function create(FileDaoImpl $dao): FileDaoImpl;

    public function uploadFile(UploadedFile|null|array $file): FileDaoImpl;
}
