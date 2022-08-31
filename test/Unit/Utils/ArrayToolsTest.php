<?php
/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace HyperfTest\Unit\Utils;

use App\Utils\ArrayTools;
use HyperfTest\HttpTestCase;

/**
 * @internal
 */
class ArrayToolsTest extends HttpTestCase
{
    public function testPartsWhenSuccessThenSuccess(): void
    {
        $array = $result = [
            'a' => 1,
            'b' => 2,
            'c' => 3,
        ];
        unset($result['c']);


        $this->assertEquals($result, ArrayTools::parts($array, ['a', 'b']));
    }

    public function testArrayKsortWhenSuccessThenSuccess(): void
    {
        $array = [
            'c' => 1,
            'b' => [
                'c' => 1,
                'a' => 2,
                'b' => 3,
            ],
            'a' => 3,
        ];
        ArrayTools::arrayKsort($array);

        $this->assertEquals([
            'a' => 3,
            'b' => [
                'a' => 2,
                'b' => 3,
                'c' => 1,
            ],
            'c' => 1,
        ], $array);
    }

    public function testTowPartsWhenSuccessThenSuccess(): void
    {
        $array = [
            ['a' => 1, 'b' => 2, 'c' => 3],
            ['a' => 1, 'b' => 2, 'c' => 3],
        ];

        $this->assertEquals([
            ['a' => 1, 'b' => 2],
            ['a' => 1, 'b' => 2],
        ], ArrayTools::towParts($array, ['a', 'b']));
    }

    public function testRemoveVoidWhenSuccessThenSuccess(): void
    {
        $array = [
            'a' => '',
            'b' => null,
            'c' => 1,
            'd' => ['a' => '', 'b' => null, 'c' => 1],
        ];

        $this->assertEquals([
            'c' => 1,
            'd' => ['c' => 1],
        ], ArrayTools::removeVoid($array));
    }

    public function testConversionKeyUcWordsWhenSuccessThenSuccess(): void
    {
        $array = [
            'a_b_c' => 0,
            'functions' => ['a_b_c' => 0],
        ];
        $array2 = ArrayTools::conversionKeyUcWords($array);

        $this->assertEquals([
            'aBC' => 0,
            'functions' => ['aBC' => 0],
        ], ArrayTools::conversionKeyUcWords($array));

        $this->assertEquals($array, ArrayTools::conversionKeyUcWords($array2, false));
    }
}
