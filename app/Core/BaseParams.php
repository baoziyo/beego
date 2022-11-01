<?php
/*
 * Sunny 2022/10/13 下午3:04
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Core;

abstract class BaseParams
{
    protected array $data;

    public function __set(mixed $name, mixed $value): self
    {
        $this->data[$name] = $value;

        return $this;
    }

    public function __get(mixed $name): mixed
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        throw new \RuntimeException("{$name} is not exist in fields.");
    }

    public function __isset(mixed $name): bool
    {
        return isset($this->data[$name]);
    }

    public function __unset(mixed $name): self
    {
        unset($this->data[$name]);

        return $this;
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->__isset($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->__get($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): BaseParams
    {
        return $this->__set($offset, $value);
    }

    public function offsetUnset(mixed $offset): self
    {
        return $this->__unset($offset);
    }

    public function toArray(): array
    {
        $this->handelData();
        return $this->data;
    }

    abstract public function handelData(): void;
}
