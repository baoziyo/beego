<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\User\Service\Impl;

use App\Biz\Role\Service\RoleService;
use App\Biz\User\Config\UserSourceStrategy;
use App\Biz\User\Dao\UserDaoImpl;
use App\Biz\User\Exception\UserException;
use App\Biz\User\Service\UserService;
use App\Core\Biz\Service\Impl\BaseServiceImpl;
use Hyperf\Database\Model\Collection;
use Hyperf\DbConnection\Db;
use Hyperf\Utils\Str;

class UserServiceImpl extends BaseServiceImpl implements UserService
{
    public function get(mixed $id): UserDaoImpl|null
    {
        return UserDaoImpl::findFromCache($id);
    }

    /**
     * @param array $ids
     * @return Collection<int,UserDaoImpl>
     */
    public function find(array $ids): Collection
    {
        return UserDaoImpl::findManyFromCache($ids);
    }

    public function create(array $fields): UserDaoImpl
    {
        $dao = new UserDaoImpl();
        $dao->fill($fields);
        $dao->save();

        return $dao;
    }

    public function register(array $data): UserDaoImpl
    {
        if (!isset($data['source']) || !$this->validateSource($data['source'])) {
            throw new UserException(UserException::USER_SOURCE_ERROR);
        }

        $data = $this->getUserSourceStrategy($data['source'])->buildRegisterParams($data);
        [$data['password'], $data['salt']] = $this->generatePassword($data['password']);

        Db::beginTransaction();
        try {
            $registerUser = $this->create($data);
            Db::commit();
        } catch (\Throwable $e) {
            Db::rollBack();
            throw new UserException(UserException::REGISTER_USER_ERROR);
        }

        return $registerUser;
    }

    public function login(array $data): UserDaoImpl
    {
        if (!isset($data['source']) || !$this->validateSource($data['source'])) {
            throw new UserException(UserException::USER_SOURCE_ERROR);
        }

        $userId = $this->getUserSourceStrategy($data['source'])->handleLogin($data);

        return $this->getUser($userId);
    }

    public function getUser(mixed $id): UserDaoImpl
    {
        $user = $this->get($id);
        if ($user === null) {
            throw new UserException(UserException::NOT_FOUND_LOGIN_USER);
        }

        $role = $this->getRoleService()->get($user->role);
        if ($role === null) {
            return $user;
        }

        $user['roleName'] = empty($role->name) ? '普通用户' : $role->name;
        $user['roleId'] = empty($role->id) ? 0 : $role->id;
        $user['roleCode'] = empty($role->code) ? '' : $role->code;

        return $user;
    }

    public function getCurrentUser(): UserDaoImpl
    {
        $currentUserId = $this->biz->getContext()::get('currentUserId');

        return $this->getUser($currentUserId);
    }

    public function getByName(string $name): UserDaoImpl
    {
        $userId = UserDaoImpl::query()->where('name', $name)->value('id');

        return $this->getUser($userId);
    }

    /**
     * @return array[password,salt]
     */
    public function generatePassword(string $password, string $salt = ''): array
    {
        if ($salt === '') {
            $salt = Str::random();
        }

        $password .= '{' . $salt . '}';
        $digest = hash('sha512', $password, true);
        for ($i = 1; $i < 5000; ++$i) {
            $digest = hash('sha512', $digest . $salt, true);
        }

        return [base64_encode($digest), $salt];
    }

    public function search(array $conditions = [], array $order = ['id', 'desc'], int $start = 0, int $limit = 10): array
    {
        $conditions = $this->buildConditions($conditions);
        $users = UserDaoImpl::query()
            ->where($conditions)
            ->orderBy(...$order)
            ->skip($start)
            ->take($limit)
            ->get();

        if ($users->isEmpty()) {
            return [];
        }

        return $users->toArray();
    }

    public function count(array $conditions = []): int
    {
        $conditions = $this->buildConditions($conditions);
        return UserDaoImpl::query()->where($conditions)->count();
    }

    private function buildConditions(array $conditions = []): array
    {
        return $conditions;
    }

    private function getUserSourceStrategy(string $type): UserSourceStrategy
    {
        if (!isset(self::USER_SOURCE_STRATEGY_TYPE[$type])) {
            throw new UserException(UserException::TOKEN_TYPE_ERROR);
        }

        return make(self::USER_SOURCE_STRATEGY_TYPE[$type], [$this->biz]);
    }

    private function validateSource(string $type): bool
    {
        return array_key_exists($type, self::USER_SOURCE_STRATEGY_TYPE);
    }

    private function getRoleService(): RoleService
    {
        return $this->biz->getService('Role:Role');
    }
}
