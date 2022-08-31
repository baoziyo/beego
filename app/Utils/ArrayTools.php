<?php
/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Utils;

class ArrayTools extends App
{
    public static function parts(array $array, array $keys, bool $strict = false): array
    {
        foreach (array_keys($array) as $key) {
            if (!in_array($key, $keys, $strict)) {
                unset($array[$key]);
            }
        }
        return $array;
    }

    /**
     * 多维数据按键值进行升序排序.
     */
    public static function arrayKsort(mixed &$array): array
    {
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    self::arrayKsort($value);
                    $array[$key] = $value;
                }
            }
            ksort($array);
        }
        return [];
    }

    public static function towParts(array $array, array $keys): array
    {
        $newArray = [];
        foreach ($array as $item) {
            if (is_array($item)) {
                $newArray[] = self::parts($item, $keys);
            }
        }
        return $newArray;
    }

    public static function removeVoid(array $array): array
    {
        foreach ($array as $key => &$value) {
            if ($value === '' || $value === null) {
                unset($array[$key]);
            }
            if (is_array($value)) {
                $value = self::removeVoid($value);
            }
        }
        return $array;
    }

    public static function conversionKeyUcWords(array $array, bool $model = true): array
    {
        $newArray = [];
        foreach ($array as $key => $value) {
            if ($model && strpos((string)$key, '_')) {
                $key = ucwords(str_replace('_', ' ', $key));
                $key = str_replace(' ', '', lcfirst($key));
            }

            if (!$model) {
                $key = preg_replace_callback('/([A-Z]+)/', static function ($matches) {
                    return implode(array_map(static function ($item) {
                        return '_' . strtolower($item);
                    }, str_split($matches[0])));
                }, $key);
                if ($key !== null && preg_replace('/_{2,}/', '_', $key) !== null) {
                    $key = trim(preg_replace('/_{2,}/', '_', $key), '_');
                }
            }

            if (is_array($value)) {
                $newArray[$key] = self::conversionKeyUcWords($value, $model);
            } else {
                $newArray[$key] = $value;
            }
        }

        return $newArray;
    }
}
