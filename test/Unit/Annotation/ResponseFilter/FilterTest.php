<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace HyperfTest\Unit\Annotation\ResponseFilter;

use Hyperf\Testing\Client;
use HyperfTest\HttpTestCase;

/**
 * @internal
 */
class FilterTest extends HttpTestCase
{
    public function testFilterSimpleWhenSuccessThenSuccess(): void
    {
        $client = make(Client::class);

        $result = $client->get('/unit_test/annotation/filter/simple', []);

        $this->assertEquals([
            'test1' => 'test1',
            'test2' => 'test2',
        ], $result['data']);
    }

    public function testFilterComplexWhenSuccessThenSuccess(): void
    {
        $client = make(Client::class);

        $result = $client->get('/unit_test/annotation/filter/complex', []);

        $this->assertEquals([
            'count' => 2,
            'list' => [
                ['test1' => 'test1', 'test2' => 'test2'],
                ['test1' => 'test1', 'test2' => 'test2'],
            ],
        ], $result['data']);
    }

    public function testFilterComplex2WhenSuccessThenSuccess(): void
    {
        $client = make(Client::class);

        $result = $client->get('/unit_test/annotation/filter/complex2', []);

        $this->assertEquals([
            ['test1' => 'test1', 'test2' => 'test2'],
            ['test1' => 'test1', 'test2' => 'test2'],
        ], $result['data']);
    }

    public function testFilterIdToStringWhenSuccessThenSuccess(): void
    {
        $client = make(Client::class);

        $result = $client->get('/unit_test/annotation/filter/id_to_string', []);

        $this->assertEquals(true, is_string($result['data']['id']));
        $this->assertEquals(true, is_string($result['data']['list']['id']));
    }
}
