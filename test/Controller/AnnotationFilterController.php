<?php
/*
 * Sunny 2021/11/24 下午5:37
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace HyperfTest\Controller;

use App\Annotation\ResponseFilter\ResponseFilter;
use App\Controller\AbstractController;
use HyperfTest\Controller\Filter\AnnotationFilter;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class AnnotationFilterController extends AbstractController
{
    #[ResponseFilter(class: AnnotationFilter::class)]
    public function simple(): PsrResponseInterface
    {
        return $this->buildRequest([
            'test1' => 'test1',
            'test2' => 'test2',
            'test3' => 'test3',
        ]);
    }

    #[ResponseFilter(class: AnnotationFilter::class, mode: 'complex')]
    public function complex(): PsrResponseInterface
    {
        return $this->buildRequest([
            'count' => 2,
            'list' => [
                ['test1' => 'test1', 'test2' => 'test2', 'test3' => 'test3'],
                ['test1' => 'test1', 'test2' => 'test2', 'test3' => 'test3'],
            ],
        ]);
    }

    #[ResponseFilter(class: AnnotationFilter::class, mode: 'complex')]
    public function complex2(): PsrResponseInterface
    {
        return $this->buildRequest([
            ['test1' => 'test1', 'test2' => 'test2', 'test3' => 'test3'],
            ['test1' => 'test1', 'test2' => 'test2', 'test3' => 'test3'],
        ]);
    }

    #[ResponseFilter(class: AnnotationFilter::class)]
    public function idToString(): PsrResponseInterface
    {
        return $this->buildRequest([
            'id' => 1,
            'list' => [
                'id' => 1,
            ],
        ]);
    }
}
