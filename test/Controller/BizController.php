<?php
/*
 * Sunny 2022/8/31 上午11:53
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */


declare(strict_types=1);

namespace HyperfTest\Controller;

use App\Controller\AbstractController;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class BizController extends AbstractController
{
    public function getVersion(): PsrResponseInterface
    {
        return $this->buildRequest($this->biz->getVersion());
    }
}
