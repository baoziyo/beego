<?php
/*
 * Sunny 2021/11/24 下午8:14
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\File\Service\Impl;

use App\Biz\File\Exception\FileException;
use App\Biz\File\Service\FileService;
use App\Core\Biz\Service\Impl\BaseServiceImpl;
use App\Utils\CharsTools;
use Hyperf\HttpMessage\Upload\UploadedFile;

class FileServiceImpl extends BaseServiceImpl implements FileService
{
    public function uploadFile(UploadedFile $file): string
    {
        if ($file->getRealPath() === false) {
            throw new FileException(FileException::INVALID_FILE);
        }
        $stream = fopen($file->getRealPath(), 'rb+');
        if ($stream === false) {
            throw new FileException(FileException::INVALID_FILE);
        }
        $filePath = 'uploads/' . date('Y-m-d') . '/' . CharsTools::generateGuid() . '.' . $file->getExtension();
        $this->biz->getFileSystem()->writeStream($filePath, $stream);
        fclose($stream);

        return $filePath;
    }
}
