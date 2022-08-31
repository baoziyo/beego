<?php

declare(strict_types=1);

namespace HyperfTest\Controller\Filter;

use App\Annotation\ResponseFilter\Filter;

class AnnotationFilter extends Filter
{
    protected array $fields = [
        'test1', 'test2',
        'id', 'list',
    ];
}
