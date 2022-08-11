<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Annotation\ResponseFilter;

use Attribute;
use Hyperf\Di\Annotation\AbstractAnnotation;

#[Attribute(Attribute::TARGET_METHOD)]
class ResponseFilter extends AbstractAnnotation
{
    public function __construct(public ?string $class = null, public ?string $mode = 'simple')
    {
    }
}
