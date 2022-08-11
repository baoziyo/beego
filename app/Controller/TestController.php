<?php
/*
 * Sunny 2021/11/24 下午5:37
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class TestController extends AbstractController
{
    public function get(): PsrResponseInterface
    {
        return $this->buildRequest('hello world!');
    }

    public function search(): PsrResponseInterface
    {
        return $this->buildRequest('hello world!');
    }

    public function create(): PsrResponseInterface
    {
        return $this->buildRequest('hello world!');
    }

    public function update(): PsrResponseInterface
    {
        return $this->buildRequest('hello world!');
    }

    public function patch(): PsrResponseInterface
    {
        return $this->buildRequest('hello world!');
    }

    public function delete(): PsrResponseInterface
    {
        return $this->buildRequest('hello world!');
    }
}
