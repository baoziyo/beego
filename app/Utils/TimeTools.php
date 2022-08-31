<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Utils;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;

class TimeTools extends App
{
    // 根据传入的时间返回时间戳
    public static function timeToCarbon(mixed $time): CarbonInterface
    {
        if ($time instanceof CarbonInterface) {
            return Carbon::instance($time);
        }

        if ($time instanceof \DateTimeInterface) {
            return Carbon::parse($time->format('Y-m-d H:i:s.u'), $time->getTimezone());
        }

        if (is_numeric($time)) {
            return Carbon::createFromTimestamp($time);
        }

        if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', (string)$time)) {
            /** @var CarbonInterface $carbon */
            $carbon = Carbon::createFromFormat('Y-m-d', $time);
            return Carbon::instance($carbon->startOfDay());
        }

        if (Carbon::hasFormat($time, 'Y-m-d H:i:s')) {
            /** @var CarbonInterface $carbon */
            $carbon = Carbon::createFromFormat('Y-m-d H:i:s', $time);

            return $carbon->clone();
        }

        return Carbon::parse($time);
    }

    // 获取传入的开始时间+天数
    public static function getIntervalEveryDay(mixed $startTime, int $day = 1): array
    {
        $startTime = self::timeToCarbon($startTime)->startOfDay();
        $endTime = Carbon::parse($startTime)->addDays($day - 1);
        $dates = [];

        foreach (CarbonPeriod::between($startTime, $endTime) as $date) {
            /** @var CarbonInterface $dateCarbon */
            $dateCarbon = $date;
            $dates[] = $dateCarbon->startOfDay()->toDateTimeString();
        }

        return $dates;
    }

    // 根据旧的开始时间-结束时间和新的开始时间到结束时间生成缺少的时间
    public static function generateStartAndEndByInterval(mixed $oldStartTime, mixed $oldEndTime, mixed $newStartTime, mixed $newEndTime): array
    {
        $oldStartTime = self::timeToCarbon($oldStartTime)->startOfDay()->subMicro();
        $oldEndTime = self::timeToCarbon($oldEndTime)->endOfDay()->addMicros();
        $newStartTime = self::timeToCarbon($newStartTime)->startOfDay();
        $newEndTime = self::timeToCarbon($newEndTime)->endOfDay();

        $times = [];
        if ($oldStartTime > $newStartTime) {
            foreach (CarbonPeriod::since($newStartTime)->days()->until($oldStartTime) as $time) {
                /** @var CarbonInterface $carbonTime */
                $carbonTime = $time;
                $times[] = $carbonTime->startOfDay()->toDateTimeString();
            }
        }

        if ($newEndTime > $oldEndTime) {
            foreach (CarbonPeriod::since($oldEndTime)->days()->until($newEndTime) as $time) {
                /** @var CarbonInterface $carbonTime */
                $carbonTime = $time;
                $times[] = $carbonTime->startOfDay()->toDateTimeString();
            }
        }

        return $times;
    }

    // 判断传入的时间是否时连续的
    public static function judgeTimeIsContinuous(array $times, int $day): bool
    {
        $newTimes = self::getIntervalEveryDay(min($times), $day);
        foreach ($times as $time) {
            $time = self::timeToCarbon($time)->startOfDay()->toDateTimeString();
            if (!in_array($time, $newTimes, true)) {
                return false;
            }
        }
        return true;
    }
}
