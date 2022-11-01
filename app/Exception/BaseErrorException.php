<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Exception;

use Hyperf\Constants\Annotation\Constants;
use Hyperf\Constants\ConstantsCollector;
use Hyperf\Constants\Exception\ConstantsException;
use Hyperf\Contract\TranslatorInterface;
use Hyperf\Server\Exception\ServerException;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Str;
use Throwable;

#[Constants]
class BaseErrorException extends ServerException
{
    public function __construct(int $code = 0, string $message = null, Throwable $previous = null, $params = [])
    {
        if (is_null($message)) {
            $message = self::getTip($code, $params);
        }
        parent::__construct($message, $code, $previous);
    }

    /**
     * @throws ConstantsException
     */
    public static function __callStatic(string $name, array $arguments)
    {
        if (!Str::startsWith($name, 'get')) {
            throw new ConstantsException('The function is not defined!');
        }
        if (count($arguments) === 0) {
            throw new ConstantsException('The Code is required');
        }
        $code = $arguments[0];
        $name = strtolower(substr($name, 3));
        $class = static::class;
        $message = ConstantsCollector::getValue($class, $code, $name);
        array_shift($arguments);
        $result = self::translate($message, $arguments);
        // If the result of translate doesn't exist, the result is equal with message, so we will skip it.
        if ($result && $result !== $message) {
            return $result;
        }
        $count = count($arguments[0]);
        if ($count > 0) {
            return sprintf($message, ...(array) $arguments[0]);
        }
        return $message;
    }

    protected static function translate(string $key, array $arguments): ?string
    {
        if (!ApplicationContext::hasContainer() || !ApplicationContext::getContainer()->has(TranslatorInterface::class)) {
            return null;
        }
        $replace = $arguments[0] ?? [];
        if (!is_array($replace)) {
            return null;
        }
        $translator = ApplicationContext::getContainer()->get(TranslatorInterface::class);
        return $translator->trans($key, $replace);
    }
}
