<?php
/*
 * Sunny 2021/11/24 下午8:14
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\File\Service\Impl;

use App\Biz\File\Dao\FileDaoImpl;
use App\Biz\File\Exception\FileException;
use App\Biz\File\Fields\CreateFileFields;
use App\Biz\File\Service\FileService;
use App\Core\Biz\Service\Impl\BaseServiceImpl;
use App\Utils\CharsTools;
use Hyperf\Database\Model\Collection;
use Hyperf\HttpMessage\Upload\UploadedFile;

class FileServiceImpl extends BaseServiceImpl implements FileService
{
    public function get(int $id): FileDaoImpl|null
    {
        return FileDaoImpl::findFromCache($id);
    }

    public function getById(int $id): FileDaoImpl
    {
        $file = $this->get($id);
        if ($file === null) {
            throw new FileException(FileException::NOT_FOUND);
        }

        return $this->bindFields($file);
    }

    /**
     * @param array $ids
     * @return Collection<int,FileDaoImpl>
     */
    public function find(array $ids): Collection
    {
        $files = FileDaoImpl::findManyFromCache($ids);
        $files->map(function ($file) {
            return $this->bindFields($file);
        });

        return $files;
    }

    public function create(FileDaoImpl $dao): FileDaoImpl
    {
        $currentUser = $this->biz->getCurrentUser();
        $dao->createdUserId = $currentUser->id;

        $dao->save();

        return $dao;
    }

    public function uploadFile(UploadedFile|null|array $file): FileDaoImpl
    {
        if ($file === null || is_array($file) || $file->getRealPath() === false) {
            throw new FileException(FileException::INVALID_FILE);
        }
        $stream = fopen($file->getRealPath(), 'rb+');
        if ($stream === false) {
            throw new FileException(FileException::INVALID_FILE);
        }
        $filePath = 'uploads/' . date('Y-m-d') . '/' . CharsTools::generateGuid() . '.' . $file->getExtension();
        $this->biz->getFileSystem()->writeStream($filePath, $stream);
        fclose($stream);

        $fields = new FileDaoImpl();
        $fields->setName($file->getClientFilename());
        $fields->setLocalPath($filePath);
        $fileDao = $this->create($fields);

        return $this->bindFields($fileDao);
    }

    protected function bindFields(FileDaoImpl $file): FileDaoImpl
    {
        $file->path = env('APP_DOMAIN') . '/' . $file->localPath;

        return $file;
    }
}
