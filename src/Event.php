<?php
namespace Froq\Event;

final class Event
{
    private $name;
    private $func;
    private $funcArgs = [];
    private $once = true;

    final public function __construct(string $name, callable
        $func = null, array $funcArgs = null, bool $once = true)
    {
        $this->name = $name;
        if ($func) {
            $this->setFunc($func);
        }
        if ($funcArgs) {
            $this->setFuncArgs($funcArgs);
        }
        $this->once = $once;
    }

    final public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
    final public function getName(): string
    {
        return $this->name;
    }

    final public function setFunc(callable $func): self
    {
        $this->func = $func;
        return $this;
    }
    final public function getFunc(): callable
    {
        return $this->func;
    }

    final public function setFuncArgs(array $funcArgs): self
    {
        $this->funcArgs = $funcArgs;
        return $this;
    }
    final public function getFuncArgs(): array
    {
        return $this->funcArgs;
    }

    final public function setOnce(bool $once): self
    {
        $this->once = $once;
        return $this;
    }
    final public function getOnce(): bool
    {
        return $this->once;
    }
    final public function isOnce(): bool
    {
        return ($this->once == true);
    }
}
