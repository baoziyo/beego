<?php

/*
 * Sunny 2022/8/30 下午5:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace HyperfTest\Unit\Utils;

use App\Utils\TimeTools;
use Carbon\Carbon;
use HyperfTest\HttpTestCase;

/**
 * @internal
 */
class TimeToolsTest extends HttpTestCase
{
    public function testTimeToCarbonWhenSuccessThenSuccess(): void
    {
        $time = time();
        $carbon = Carbon::parse(date('Y-m-d H:i:s', $time));

        $this->assertEquals(true, (bool)TimeTools::timeToCarbon(null));
        $this->assertEquals($carbon, TimeTools::timeToCarbon(Carbon::parse(date('Y-m-d H:i:s', $time))));
        $this->assertEquals($carbon, TimeTools::timeToCarbon(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', $time))));
        $this->assertEquals($carbon, TimeTools::timeToCarbon($time));
        $this->assertEquals($carbon, TimeTools::timeToCarbon(date('Y-m-d H:i:s', $time)));
        $this->assertEquals($carbon, TimeTools::timeToCarbon((string)$time));
    }

    public function testGetIntervalEveryDayWhenSuccessThenSuccess(): void
    {
        $dates = TimeTools::getIntervalEveryDay('2020-01-01', 3);

        $this->assertEquals([
            '2020-01-01 00:00:00',
            '2020-01-02 00:00:00',
            '2020-01-03 00:00:00',
        ], $dates);
    }

    public function testGenerateStartAndEndByIntervalWhenSuccessThenSuccess(): void
    {
        $this->assertEquals([
            '2020-01-06 00:00:00',
            '2020-01-07 00:00:00',
            '2020-01-08 00:00:00',
            '2020-01-09 00:00:00',
            '2020-01-10 00:00:00',
        ], TimeTools::generateStartAndEndByInterval('2020-01-01', '2020-01-05', '2020-01-01', '2020-01-10'));

        $this->assertEquals([
            '2020-01-01 00:00:00',
            '2020-01-02 00:00:00',
            '2020-01-03 00:00:00',
            '2020-01-04 00:00:00',
            '2020-01-05 00:00:00',
            '2020-01-06 00:00:00',
            '2020-01-07 00:00:00',
            '2020-01-08 00:00:00',
        ], TimeTools::generateStartAndEndByInterval('2020-01-09', '2020-01-10', '2020-01-01', '2020-01-10'));
    }

    public function testJudgeTimeIsContinuousWhenSuccessThenSuccess(): void
    {
        $this->assertEquals(true, TimeTools::judgeTimeIsContinuous(['2020-01-01', '2020-01-02', '2020-01-03'], 3));
        $this->assertEquals(false, TimeTools::judgeTimeIsContinuous(['2020-01-01', '2020-01-02', '2020-01-03'], 2));
    }
}
