<?php
namespace Froq\Event;

use Froq\Collection\Collection;

final class Events
{
    private $stack = [];

    public function setStack(array $stack): self
    {
        $this->stack = $stack;
        return $this;
    }

    public function getStack(): array
    {
        return $this->stack;
    }

    final public function on(string $name,
        callable $func, array $funcArgs = null, bool $once = true): self
    {
        $name = $this->normalizeName($name);
        $this->stack[$name] = new Event($name, $func, $funcArgs, $once);
        return $this;
    }

    final public function off(string $name): self
    {
        unset($this->stack[$this->normalizeName($name)]);
        return $this;
    }

    final public function has(string $name): bool
    {
        return isset($this->data[$this->normalizeName($name)]);
    }

    final public function fire(string $name, ...$funcArgs)
    {
        $name = $this->normalizeName($name);
        if (isset($this->stack[$name])) {
            $event = $this->stack[$name];
            if ($even->isOnce()) {
                $this->off($name);
            }
            return call_user_func_array(
                $event->getFunc(), array_merge($event->getFuncArgs(), $funcArgs));
        }
    }

    final private function normalizeName(string $name): string
    {
        return strtolower($name);
    }
}
