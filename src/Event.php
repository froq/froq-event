<?php
namespace Froq\Event;

final class Event
{
    private $name;
    private $func;
    private $funcArgs = [];

    final public function __construct(string $name, callable $func = null, array $funcArgs = null)
    {
        $this->name = $name;
        if ($func) {
            $this->setFunc($func);
        }
        if ($funcArgs) {
            $this->setFuncArgs($funcArgs);
        }
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
}
