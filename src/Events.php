<?php
namespace Froq\Event;

use Froq\Collection\Collection;

final class Events extends Collection
{
    final public function on(string $name, callable $func, array $funcArgs = null): self
    {
        $name = $this->normalizeName($name);
        $this->set($name, new Event($name, $func, $funcArgs));
        return $this;
    }

    final public function off(string $name): self
    {
        $this->del($this->normalizeName($name));
        return $this;
    }

    final public function has(string $name): bool
    {
        return $this->offsetExists($this->normalizeName($name));
    }

    final public function fire(string $name, ...$funcArgs)
    {
        $name = $this->normalizeName($name);
        $event = $this->get($name);
        if ($event) {
            $func = $event->getFunc();
            $funcArgs = array_merge($event->getFuncArgs(), $funcArgs);
            return call_user_func_array($func, $funcArgs);
        }
    }

    final private function normalizeName(string $name): string
    {
        return strtolower($name);
    }
}
