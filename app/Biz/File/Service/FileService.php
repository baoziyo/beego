<?php
/*
 * Sunny 2021/11/24 下午8:14
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\File\Service;

use App\Core\Biz\Service\BaseService;
use Hyperf\HttpMessage\Upload\UploadedFile;

interface FileService extends BaseService
{
    public function uploadFile(UploadedFile $file): string;
}
