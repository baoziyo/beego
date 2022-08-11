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
     * @param mixed $array
     */
    public static function arrayKsort(&$array): array
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

    public static function group(array $array, $key): array
    {
        $grouped = [];
        foreach ($array as $item) {
            if (empty($grouped[$item[$key]])) {
                $grouped[$item[$key]] = [];
            }
            $grouped[$item[$key]][] = $item;
        }
        return $grouped;
    }

    public static function removeVoid($array): array
    {
        if (empty($array) || !is_array($array)) {
            return $array;
        }
        foreach ($array as $key => &$value) {
            if ($value === '' || $value === null) {
                unset($array[$key]);
            }
            $value = self::removeVoid($value);
        }
        return $array;
    }

    public static function conversionUcwords($array, $model = true): array
    {
        if (is_array($array)) {
            $newArray = [];
            foreach ($array as $key => $value) {
                if ($model && strpos((string)$key, '_')) {
                    $key = ucwords(str_replace('_', ' ', $key));
                    $key = str_replace(' ', '', lcfirst($key));
                }
                if (!$model) {
                    $key = preg_replace_callback('/([A-Z]+)/', static function ($matches) {
                        return '_' . strtolower($matches[0]);
                    }, $key);
                    $key = trim(preg_replace('/_{2,}/', '_', $key), '_');
                }
                $newArray[$key] = self::conversionUcwords($value, $model);
            }
        } else {
            return $array;
        }
        return $newArray;
    }
}