<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Utils;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class TimeTools extends App
{
    // 根据传入的时间返回时间戳
    public static function timeToTimeNumber($time): float|int|string|null
    {
        if ($time === null || $time === '' || $time === '0000-00-00 00:00:00') {
            return null;
        }
        return Carbon::parse(is_numeric($time) ? (int)$time : $time)->tz(Carbon::now()->tz)->timestamp;
    }

    // 获取传入的开始时间+天数
    public static function getIntervalEveryDay($startTime, $day = 1): array
    {
        $startTime = Carbon::parse(is_numeric($startTime) ? (int)$startTime : $startTime)->tz(Carbon::now()->tz)->startOfDay();
        $endTime = Carbon::parse($startTime)->tz(Carbon::now()->tz)->addDays($day - 1);
        $dates = [];
        foreach (CarbonPeriod::between($startTime, $endTime) as $date) {
            $dates[] = $date->startOfDay()->toDateTimeString();
        }
        return $dates;
    }

    // 根据旧的开始时间-结束时间和新的开始时间到结束时间生成缺少的时间
    public static function generateStartAndEndByInterval(array $oldTimes, array $newTimes): array
    {
        foreach ($oldTimes as &$oldTime) {
            $oldTime = self::timeToTimeNumber($oldTime);
        }
        foreach ($newTimes as &$newTime) {
            $newTime = self::timeToTimeNumber($newTime);
        }
        $times = [];
        $oldStartTime = Carbon::parse(min($oldTimes))->tz(Carbon::now()->tz)->startOfDay()->subMicro();
        $oldEndTime = Carbon::parse(max($oldTimes))->tz(Carbon::now()->tz)->endOfDay()->addMicros();
        $newStartTime = Carbon::parse(min($newTimes))->tz(Carbon::now()->tz)->startOfDay();
        $newEndTime = Carbon::parse(max($newTimes))->tz(Carbon::now()->tz)->endOfDay();
        if ($oldStartTime > $newStartTime) {
            foreach (CarbonPeriod::since($newStartTime)->days(1)->until($oldStartTime) as $time) {
                $times[] = $time->tz(Carbon::now()->tz)->startOfDay()->toDateTimeString();
            }
        }
        if ($newEndTime > $oldEndTime) {
            foreach (CarbonPeriod::since($oldEndTime)->days(1)->until($newEndTime) as $time) {
                $times[] = $time->tz(Carbon::now()->tz)->startOfDay()->toDateTimeString();
            }
        }
        return $times;
    }

    // 判断传入的时间是否时连续的
    public static function judgeTimeIsContinuous($times, $day): bool
    {
        $newTimes = self::getIntervalEveryDay(min($times), $day);
        foreach ($times as $time) {
            $time = Carbon::parse(is_numeric($time) ? (int)$time : $time)->tz(Carbon::now()->tz)->startOfDay()->toDateTimeString();
            if (!in_array($time, $newTimes, true)) {
                return false;
            }
        }
        return true;
    }
}