<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Annotation\ResponseFilter;

use App\Core\Biz\Container\Biz;
use App\Utils\ArrayTools;
use Hyperf\Utils\Codec\Json;

abstract class Filter
{
    /**
     * 数组模式.
     */
    public const SIMPLE_MODE = 'simple';

    /**
     * 列表模式.
     */
    public const COMPLEX_MODE = 'complex';

    protected Biz $biz;

    protected string $mode = self::SIMPLE_MODE;

    protected string $fieldsName = 'fields';

    public function __construct(Biz $biz)
    {
        $this->biz = $biz;
    }

    public function setMode(string $mode): void
    {
        $this->mode = $mode;
    }

    public function filter(string $json): string
    {
        $data = Json::decode($json);
        if ($this->mode === self::SIMPLE_MODE) {
            $data['data'] = $this->simple($data['data']);
        }
        if ($this->mode === self::COMPLEX_MODE) {
            $data['data'] = $this->complex($data['data']);
        }

        $data = $this->handleArrayToString($data);
        return Json::encode($data);
    }

    protected function handleArrayToString(array $data): array
    {
        foreach ($data as $key => $item) {
            if (is_array($item)) {
                $data[$key] = $this->handleArrayToString($item);
            }

            if (!is_array($item) && is_string($key) && stripos(strtolower($key), 'id') !== false) {
                $data[$key] = (string)$item;
            }
        }

        return $data;
    }

    protected function simpleFields(array $data): array
    {
        return $data;
    }

    protected function complexFields(array $data): array
    {
        return $data;
    }

    private function simple(array $data): array
    {
        $property = $this->mode . 'Fields';
        if (property_exists($this, $this->fieldsName) && $this->{$this->fieldsName}) {
            $data = ArrayTools::parts($data, $this->{$this->fieldsName});
        }
        if (method_exists($this, $property)) {
            $data = $this->{$property}($data);
        }

        return $data;
    }

    private function complex(array $data): array
    {
        $property = $this->mode . 'Fields';
        if (property_exists($this, $this->fieldsName) && $this->{$this->fieldsName}) {
            if (isset($data['list'])) {
                foreach ($data['list'] as &$item) {
                    $item = ArrayTools::parts($item, $this->{$this->fieldsName});
                }
                unset($item);

                $this->{$this->fieldsName} = array_merge($this->{$this->fieldsName}, ['count', 'list']);

                $data = $this->simple($data);
            } else {
                foreach ($data as &$item) {
                    $item = ArrayTools::parts($item, $this->{$this->fieldsName});
                }
                unset($item);
            }
        }

        if (method_exists($this, $property)) {
            $data = $this->{$property}($data);
        }

        return $data;
    }
}
