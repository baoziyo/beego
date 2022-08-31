<?php

/*
 * Sunny 2022/8/30 下午5:30
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

namespace HyperfTest\Unit\Utils;

use App\Utils\CharsTools;
use HyperfTest\HttpTestCase;

/**
 * @internal
 */
class CharsToolsTest extends HttpTestCase
{
    public function testGetRandCharWhenSuccessThenSuccess(): void
    {
        $this->assertEquals(5, strlen(CharsTools::getRandChar(5)));
        $this->assertEquals(20, strlen(CharsTools::getRandChar(20)));
    }

    public function testGenerateGuidWhenSuccessThenSuccess(): void
    {
        $this->assertEquals(32, strlen(CharsTools::generateGuid()));
    }

    public function testGenerateMd5By16BitWhenSuccessThenSuccess(): void
    {
        $this->assertEquals(16, strlen(CharsTools::generateMd5By16Bit('test')));
    }
}
