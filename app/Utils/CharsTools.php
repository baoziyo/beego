<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Utils;

class CharsTools extends App
{
    /**
     * 随机生成字符串.
     * @param mixed $len
     */
    public static function getRandChar($len): string
    {
        $a = range('a', 'z');
        $b = range('A', 'Z');
        $c = range('0', '9');
        $chars = array_merge($a, $b, $c);
        $charsLen = count($chars) - 1;
        shuffle($chars);
        $output = '';
        for ($i = 0; $i < $len; ++$i) {
            $output .= $chars[random_int(0, $charsLen)];
        }
        return $output;
    }

    /**
     * 生成GUID.
     */
    public static function generateGuid(): string
    {
        mt_srand((int)microtime() * 10000);
        $charId = strtoupper(md5(uniqid((string)mt_rand(), true)));
        $uuid = substr($charId, 0, 8) . substr($charId, 8, 4) . substr($charId, 12, 4) . substr($charId, 16, 4) . substr($charId, 20, 12);
        return strtolower($uuid);
    }

    public static function generateMd5By16Bit($string): string
    {
        return substr(md5($string), 8, 16);
    }
}