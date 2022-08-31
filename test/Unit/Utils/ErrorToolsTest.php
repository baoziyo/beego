<?php

/*
 * Sunny 2022/8/31 上午11:12
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace HyperfTest\Unit\Utils;

use App\Utils\ErrorTools;
use HyperfTest\HttpTestCase;

/**
 * @internal
 */
class ErrorToolsTest extends HttpTestCase
{
    public function testGenerateErrorInfoWhenSuccessThenSuccess(): void
    {
        try {
            throw new \InvalidArgumentException('test');
        } catch (\Exception $error) {
            $this->assertEquals([
                'file' => $error->getFile(),
                'line' => $error->getLine(),
                'message' => $error->getMessage(),
                'code' => $error->getCode(),
                'trace' => $error->getTraceAsString(),
            ], ErrorTools::generateErrorInfo($error));
        }
    }
}
